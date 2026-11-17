<?php
// Disposable database only. Never points at application/config/database.php.
if (PHP_SAPI !== 'cli') die('CLI only');
if (getenv('PRP2_TEST_ALLOW_RESET')!=='YES') die("Set PRP2_TEST_ALLOW_RESET=YES for disposable database.\n");
define('ENVIRONMENT','testing'); define('BASEPATH',realpath(__DIR__.'/../system').'/'); define('APPPATH',realpath(__DIR__.'/../application').'/'); define('FCPATH',realpath(__DIR__.'/..').'/'); define('VIEWPATH',APPPATH.'views/');
require BASEPATH.'core/Common.php'; require BASEPATH.'database/DB.php';
class TestConfig { public $settings=[]; public function load($name) {} public function item($name) { return $this->settings[$name]??null; } }
class TestLoader { public function library($name) { $ci =& get_instance(); require_once APPPATH.'libraries/'.$name.'.php'; $prop=strtolower($name); if (!isset($ci->$prop)) $ci->$prop=new $name(); } }
$ci=new stdClass();function &get_instance(){global $ci;return $ci;}
$ci->config=new TestConfig();$ci->load=new TestLoader();
$ci->config->settings['pemkab_sync']=['enabled'=>false,'storage_path'=>sys_get_temp_dir().'/prp2-test-storage-'.getmypid(),'max_photo_bytes'=>5242880,'timeout'=>2];
$conn=new mysqli('127.0.0.1','root','','',(int)getenv('PRP2_TEST_DB_PORT')?:33318);
$conn->query('DROP DATABASE IF EXISTS prp2_module_test');$conn->query('CREATE DATABASE prp2_module_test CHARACTER SET utf8mb4');$conn->select_db('prp2_module_test');
function batch($conn,$sql){if(!$conn->multi_query($sql))throw new Exception($conn->error);do{if($r=$conn->store_result())$r->free();if($conn->errno)throw new Exception($conn->error);}while($conn->more_results()&&$conn->next_result());if($conn->errno)throw new Exception($conn->error);}
batch($conn,file_get_contents(FCPATH.'prp2pemkab.sql'));batch($conn,file_get_contents(FCPATH.'sql/migrations/20261117_pemkab_terintegrasi.sql'));
$ci->db=DB(['hostname'=>'127.0.0.1','port'=>(int)getenv('PRP2_TEST_DB_PORT')?:33318,'username'=>'root','password'=>'','database'=>'prp2_module_test','dbdriver'=>'mysqli','dbprefix'=>'','pconnect'=>false,'db_debug'=>false,'cache_on'=>false,'char_set'=>'utf8mb4','dbcollat'=>'utf8mb4_general_ci','stricton'=>true,'save_queries'=>true],true);
$ci->load->library('Pemkab_service');$s=$ci->pemkab_service;$db=$s->db;
function check($v,$msg){global $n;$n++;if(!$v)throw new Exception('FAILED: '.$msg);echo "PASS $msg\n";}
function fails($fn,$msg){try{$fn();}catch(Throwable $e){check(true,$msg);return;}check(false,$msg);}
$inst=Pemkab_rules::uuid();$db->insert('pk_installation',['id'=>1,'installation_id'=>$inst,'id_kabupaten'=>1,'nama_daerah'=>'Daerah uji','created_at'=>gmdate('Y-m-d H:i:s')]);
$a=['id_user'=>1,'role'=>'admin','id_opd_pemkab'=>null];$v=['id_user'=>1,'role'=>'viewer','id_opd_pemkab'=>null];
$db->replace('pk_user_access',['id_user'=>1,'role'=>'admin']);check($s->actor(1)['role']==='admin','active account authorization');
fails(function()use($s){$s->actor(99999);},'ungranted account denied');
$o1=$s->saveOpd($a,['tahun'=>2026,'nama_opd'=>'OPD A']);$o2=$s->saveOpd($a,['tahun'=>2026,'nama_opd'=>'OPD B']);
$u=['id_user'=>1,'role'=>'opd','id_opd_pemkab'=>$o1];
$p=['tahun'=>2026,'id_opd_pemkab'=>$o1,'keperluan'=>'Pembangunan jalan','pagu'=>'1000000','nilai'=>'800000','st_kontrak'=>'P','status_3'=>'Y'];$k1=$s->saveActivity($u,$p);
$p2=$p;$p2['id_opd_pemkab']=$o2;$p2['keperluan']='Jembatan';$p2['nilai']='0';$p2['st_kontrak']='B';$k2=$s->saveActivity($a,$p2);
fails(function()use($s,$u,$k2){$s->activity($u,$k2);},'cross OPD direct ID denied');
fails(function()use($s,$v,$p){$s->saveActivity($v,$p);},'viewer cannot mutate');
fails(function()use($s,$u,$p2){$s->saveActivity($u,$p2);},'cross OPD insert denied');
$bad=$p;$bad['tahun']=2027;fails(function()use($s,$u,$bad){$s->saveActivity($u,$bad);},'year must have active OPD snapshot');
$before=$db->count_all('pk_outbox');$bad=$p;$bad['pagu']='1';fails(function()use($s,$u,$bad){$s->saveActivity($u,$bad);},'contract cannot exceed pagu');check($db->count_all('pk_outbox')===$before,'invalid edit rolls back outbox');
$f1=$s->saveRealization($u,'fisik',['id_kegiatan'=>$k1,'tanggal'=>'2026-01-20','nilai'=>'80']);
$f2=$s->saveRealization($u,'fisik',['id_kegiatan'=>$k1,'tanggal'=>'2026-02-10','nilai'=>'60']);
$s->saveRealization($u,'fisik',['id_kegiatan'=>$k1,'tanggal'=>'2026-02-10','nilai'=>'55']);
$r=$s->rows($u,2026,1);check((float)$r[0]['fisik_periode']===80.0,'physical January cutoff');
$r=$s->rows($u,2026,2);check((float)$r[0]['fisik_periode']===55.0,'physical latest date then ID, not maximum');
$ku=$s->saveRealization($u,'keuangan',['id_kegiatan'=>$k1,'tanggal'=>'2026-02-01','nilai'=>'400000']);
fails(function()use($s,$u,$k1){$s->saveRealization($u,'keuangan',['id_kegiatan'=>$k1,'tanggal'=>'2026-02-02','nilai'=>'500000']);},'overpayment rejected');
fails(function()use($s,$u,$k1){$s->saveRealization($u,'fisik',['id_kegiatan'=>$k1,'tanggal'=>'2026-02-30','nilai'=>'20']);},'invalid date rejected');
fails(function()use($s,$u,$k1){$s->saveRealization($u,'fisik',['id_kegiatan'=>$k1,'tanggal'=>'2025-02-01','nilai'=>'20']);},'cross year realization rejected');
$sum=Pemkab_rules::rekap($s->rows($a,2026,2));check($sum['persen_fisik']===27.5 && $sum['persen_keuangan']===25.0,'Sumut weighted physical and financial calculation');
check(count($s->rows($u,2026,2))===1,'OPD report scope');
$before=$db->count_all('pk_outbox');fails(function()use($s,$u,$ku,$k2){$s->saveRealization($u,'keuangan',['id'=>$ku,'id_kegiatan'=>$k2,'tanggal'=>'2026-02-01','nilai'=>'2']);},'child reparent attack denied');check($db->count_all('pk_outbox')===$before,'failed child mutation leaves no event');
$s->saveFinance($a,['jenis'=>'lra','tahun'=>2026,'bulan'=>2,'status_apbd'=>'murni','kode'=>'5','nama'=>'Belanja','nilai'=>'250000']);
fails(function()use($s,$u){$s->saveFinance($u,['jenis'=>'lra']);},'OPD cannot modify regional finance');
$ci->load->library('Pemkab_sender');$sender=$ci->pemkab_sender;
check($sender->claim()===null,'manual policy never dispatches unapproved changes');
foreach($db->get('pk_outbox')->result_array() as $e)$s->approve($a,$e['id']);
$first=$sender->claim();check($first!==null && $first['source_version']==1,'worker claims approved first version');
$other=$sender->claim();check($other['source_uuid']!==$first['source_uuid'],'concurrent worker cannot claim later version of leased entity');
$db->update('pk_outbox',['lease_until'=>'2000-01-01 00:00:00'],['status'=>'leased']);
$seen=[];$transport=function($method,$path,$body)use(&$seen){$e=json_decode($body,true);$seen[]=$e;return [200,['status'=>'accepted','installation_id'=>$e['installation_id'],'event_id'=>$e['event_id'],'source_uuid'=>$e['source_uuid'],'source_version'=>$e['source_version'],'remote_id'=>'central-'.$e['source_uuid'],'remote_version'=>$e['source_version'],'payload_sha256'=>$e['payload_sha256']]];};
$stats=$sender->run(50,$transport);check($stats['accepted']>0 && $db->where('status !=','accepted')->count_all_results('pk_outbox')===0,'lease recovery and ordered valid receipts');
$last=[];foreach($seen as $e){if(isset($last[$e['source_uuid']]))check($e['source_version']===$last[$e['source_uuid']]+1,'FIFO entity versions');$last[$e['source_uuid']]=$e['source_version'];}
$edit=$p;$edit['id']=$k1;$edit['keperluan']='Perubahan baru';$s->saveActivity($u,$edit);$e=$db->order_by('id','DESC')->limit(1)->get('pk_outbox')->row_array();check($e['status']==='awaiting','edit after approval needs new approval');$s->approve($a,$e['id']);
$broken=$sender->run(1,function(){return [200,['status'=>'accepted']];});check($broken['retry']===1,'malformed receipt cannot mark accepted');
$attempt=$db->get_where('pk_outbox',['id'=>$e['id']])->row_array();$request=$attempt['request_body'];$event=$attempt['event_id'];
$db->update('pk_outbox',['next_attempt'=>'2000-01-01'],['id'=>$e['id']]);$sender->run(1,function($m,$p,$b)use($request){check($b===$request,'retry preserves exact immutable body and event ID');return [409,[]];});check($db->get_where('pk_outbox',['id'=>$e['id']])->row_array()['status']==='conflict','conflict retained without overwrite');
$s->saveActivity($u,$edit);$new=$db->order_by('id','DESC')->limit(1)->get('pk_outbox')->row_array();$s->approve($a,$new['id']);check($sender->claim()===null,'conflict blocks subsequent versions');
$s->remove($u,'keuangan',$ku);check((float)$s->rows($u,2026,2)[0]['realisasi_periode']===0.0,'deletion immediately reflected in report');
// Files are sent separately; snapshots never contain server paths.
$dir=$s->storage();$blob=base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j6QAAAABJRU5ErkJggg==');$docUuid=Pemkab_rules::uuid();$name=$docUuid.'.png';file_put_contents($dir.'/'.$name,$blob);
$db->insert('infrastruktur_dokumentasi',['source_uuid'=>$docUuid,'id_detail'=>$f1,'file_dokumentasi'=>$name,'sha256'=>hash('sha256',$blob),'mime'=>'image/png','ukuran'=>strlen($blob)]);
$snap=$s->snapshot($k1);check(!isset($snap['fisik'][0]['dokumentasi'][0]['file_dokumentasi']),'snapshot excludes filesystem filenames');
// Sender photo staging, checksums, references and strict receipt identity.
$kphoto=$s->saveActivity($a,array_merge($p,['keperluan'=>'Uji berkas','id_opd_pemkab'=>$o2]));
$df=$s->saveRealization($a,'fisik',['id_kegiatan'=>$kphoto,'tanggal'=>'2026-03-01','nilai'=>'20']);
$uuid=Pemkab_rules::uuid(); $filename=$uuid.'.png';file_put_contents($dir.'/'.$filename,$blob);
$db->insert('infrastruktur_dokumentasi',['source_uuid'=>$uuid,'id_detail'=>$df,'file_dokumentasi'=>$filename,'sha256'=>hash('sha256',$blob),'mime'=>'image/png','ukuran'=>strlen($blob)]);
$s->transaction(function()use($s,$kphoto,$a){$s->queueActivity($kphoto,$a);});
foreach($db->get_where('pk_outbox',['status'=>'awaiting'])->result_array() as $ee)$s->approve($a,$ee['id']);
$photoCalls=0;$mockFiles=function($method,$path,$body)use(&$photoCalls,$transport){
 if($path==='/files'){$photoCalls++;$x=json_decode($body,true);check(hash('sha256',base64_decode($x['content_base64']))===$x['sha256'],'photo body matches SHA256');return [200,['source_uuid'=>$x['source_uuid'],'sha256'=>$x['sha256']]];}
 return $transport($method,$path,$body);
};$sender->run(30,$mockFiles);check($photoCalls===1,'photo upload before snapshot acknowledgement');
$s->transaction(function()use($s,$kphoto,$a){$s->queueActivity($kphoto,$a);});$ee=$db->order_by('id','DESC')->limit(1)->get('pk_outbox')->row_array();$s->approve($a,$ee['id']);file_put_contents($dir.'/'.$filename,'corrupt');
$res=$sender->run(1,$mockFiles);check($res['retry']===1,'corrupt local photo prevents accepted event');
$nrefs=$sender->references(function(){return [200,['version'=>'test-v1','items'=>[['jenis'=>'psd','kode'=>'TEST','nama'=>'Referensi simulasi','aktif'=>'Y']]]];});check($nrefs===1,'references imported transactionally');
$bad=$p;$bad['psd']='UNKNOWN';fails(function()use($s,$a,$bad){$s->saveActivity($a,$bad);},'unknown central reference denied');
check(Pemkab_rules::cents('999999999999999.99')===99999999999999999,'currency cents comparison retains precision');
// Legacy fixture mirrors old permissive SQL mode. Restore strict mode before testing migration.
$db->query("SET SESSION sql_mode=''");
$db->query("INSERT INTO ta_kontrak(id_kontrak,tahun,pagu,nilai,kd_keg,keperluan,status_3,tgl_kontrak) VALUES (99991,2026,1000,500,1,'Migrasi uji','Y','2026-01-01')");
if(!$db->get_where('data_skpd_tahun',['tahun'=>2026,'id_skpd'=>1])->row_array())$db->query("INSERT INTO data_skpd_tahun(id_skpd,nama_skpd,tahun) VALUES(1,'Dinas Pendidikan',2026)");
$db->query("INSERT INTO data_kegiatan_detail(id_kegiatan_detail,id_kegiatan,jadwal_target,realisasi) VALUES(99991,99991,'2026-01-01',30)");
$db->query("INSERT INTO data_kontrak_real(id_real,tahun,id_kontrak,nilai,tgl_realisasi) VALUES(99991,2026,99991,100,'2026-02-02')");
$db->query("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
$ci->load->library('Pemkab_legacy');$before=$db->count_all('pk_outbox');$rep=$ci->pemkab_legacy->run(2026);check(count($rep['exceptions'])>0 && $rep['eligible']===0,'unverified legacy dates and flags held');check($before===$db->count_all('pk_outbox'),'dry run does not write');
$dec=['activities'=>['99991'=>['st_kontrak'=>'P','status_1'=>'N','status_3'=>'Y']],'physical_dates'=>['99991'=>'2026-02-03']];
$rep=$ci->pemkab_legacy->run(2026,$dec);check($rep['eligible']===1,'reviewed legacy record eligible');
$rep=$ci->pemkab_legacy->run(2026,$dec,true);check($rep['migrated']===1,'legacy apply migrates verified data');$rep=$ci->pemkab_legacy->run(2026,$dec,true);check($rep['migrated']===0 && $rep['already_mapped']===1,'migration rerun is idempotent');
$db->update('data_kontrak_real',['nilai'=>101],['id_real'=>99991]);fails(function()use($ci,$dec){$ci->pemkab_legacy->run(2026,$dec,true);},'changed legacy source detected');
check($db->get_where('ta_kontrak',['id_kontrak'=>99991])->num_rows()===1,'legacy data remains intact');
$s->remove($u,'kegiatan',$k1);fails(function()use($s,$u,$k1){$s->activity($u,$k1);},'tombstoned activity unavailable');
echo "TOTAL $n checks passed\n";
