<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\siswa;
use App\wali;
use App\berkas;
class RegistrasiController extends Controller
{
    
    public function siswa(Request $request){
       // return $request;
        //Validasi form
        $this->validate($request, [
            'nama' => 'required' , 
            'nis' => 'required',
            'alamat' => 'required' , 
            'no_tlp' => 'required' , 
            'jenis_kelamin' => 'required' , 
            'tgl_lahir' =>'required' , 
            'email' => 'required|email',
            'agama_id' => 'required|numeric',
            'pict' => 'required', 
            'nama_wl' => 'required' , 
            'alamat_wl' => 'required' , 
            'no_tlp_wl' => 'required' , 
            'jenis_kelamin_wl' => 'required' , 
            'tgl_lahir_wl' =>'required' , 
            'email_wl' => 'required|email',
            'agama_wl' => 'required|numeric',
            'berkas_nis' => 'required',
            'berkas_kk' => 'required',
            'berkas_pembayaran' => 'required'
        ]);
        
        // handle foto profile siswa
        if($request->hasFile('pict')){
            
            $fileNameWithExtension = $request->file('pict')->getClientOriginalName();
            $fileName = $request->nis;
            $fileExtension = $request->file('pict')->getClientOriginalExtension();
            $fileNameToStorage = $fileName.'_'.time().'.'.$fileExtension;
            $filePath = $request->file('pict')->storeAs('public/profile_siswa' , $fileNameToStorage); 
        } 
        else {
            $filePath = 'PATH KE PROFILE UMUM';
        }
        // handle data siswa
        $siswa = siswa::create([
            'nama' => $request->nama,
            'nis'=> $request->nis,
            'alamat'=> $request->alamat,
            'no_tlp' => $request->no_tlp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir , 
            'email' => $request->email , 
            'agama_id'=>$request->agama_id , 
            'pict' => $fileNameToStorage
        ]);
        // handle data Wali
        $wali = wali::firstOrCreate([
            'nama' => $request->nama_wl , 
            'alamat' => $request->alamat_wl , 
            'no_tlp' => $request->no_tlp_wl , 
            'jenis_kelamin' => $request->jenis_kelamin_wl , 
            'tgl_lahir' => $request->tgl_lahir_wl , 
            'email' => $request->email_wl , 
            'agama_id' => $request->agama_wl
        ]);
        // insert data relasi many to many pada table  detail_siswas
        $wali->siswa()->attach($siswa->id);

        /**
         * handle berkas nis siswa 
         */
        if($request->hasFile('berkas_nis')){
            $fileNameWithExtension = $request->file('berkas_nis')->getClientOriginalName();
            $fileName = $siswa->nis.'_berkas_nis';
            $fileExtension = $request->file('berkas_nis')->getClientOriginalExtension();
            $fileNameToStorage = $fileName.'_'.time().'.'.$fileExtension;
            $filePath = $request->file('berkas_nis')->storeAs('public/berkas' , $fileNameToStorage); 
        } 
        else {
            $filePath = 'PATH KE PROFILE UMUM';
        }
        $berkas = berkas::create([
            'siswa_id' => $siswa->id,
            'path' => $fileNameToStorage,
            'jenis_berkas' => '1'
        ]);
        /**
         * handle berkas kk
         */
        if($request->hasFile('berkas_kk')){
            $fileNameWithExtension = $request->file('berkas_kk')->getClientOriginalName();
            $fileName = $siswa->nis.'_berkas_kk';
            $fileExtension = $request->file('berkas_kk')->getClientOriginalExtension();
            $fileNameToStorage = $fileName.'_'.time().'.'.$fileExtension;
            $filePath = $request->file('berkas_kk')->storeAs('public/berkas' , $fileNameToStorage); 
        } 
        else {
            $filePath = 'PATH KE PROFILE UMUM';
        }
        $berkas = berkas::create([
            'siswa_id' => $siswa->id,
            'path' => $fileNameToStorage,
            'jenis_berkas' => '2'
        ]);
        /**
         * handle berkas pembayaran
         */
        if($request->hasFile('berkas_pembayaran')){
            $fileNameWithExtension = $request->file('berkas_pembayaran')->getClientOriginalName();
            $fileName = $siswa->nis.'_berkas_pembayaran';
            $fileExtension = $request->file('berkas_pembayaran')->getClientOriginalExtension();
            $fileNameToStorage = $fileName.'_'.time().'.'.$fileExtension;
            $filePath = $request->file('berkas_pembayaran')->storeAs('public/berkas' , $fileNameToStorage); 
        } 
        else {
            $filePath = 'PATH KE PROFILE UMUM';
        }
        $berkas = berkas::create([
            'siswa_id' => $siswa->id,
            'path' => $fileNameToStorage,
            'jenis_berkas' => '3'
        ]);
        return "sukses";
   
    }
    public function view(){
        $siswa = siswa::where('verified','0')->get();
        return view('tata_usaha.validasi_siswa_baru',['siswas'=>$siswa]);
    }

    public function show($id){
        $siswa = siswa::find($id);
        return view('tata_usaha.biodata_siswa_baru',['siswa'=>$siswa]);
    }
}