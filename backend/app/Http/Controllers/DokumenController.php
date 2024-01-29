<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Requests\StoreDokumenRequest;
use App\Models\Dokumen;
use App\Models\SimRegister;
use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DokumenController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $dokumens = Dokumen::with('filepond');
            if (!is_null(request('register'))) {
                $dokumens->where('dokumens.register', request('register'));
            }

            return $this->okApiResponse(
                $dokumens->get(),
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function data()
    {
        try {

            $dokumens = Dokumen::all();

            return $this->okApiResponse(
                $dokumens,
                'Berhasil Memuat Data'
            );
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function store(StoreDokumenRequest $request)
    {
        try {

            DB::beginTransaction();

            $user = auth()->user();

            $pasien = SimRegister::with('pasien')
                ->where('REGISTER', $request->register)
                ->first();

            if (is_null($pasien)) {
                throw new ModelNotFoundException('Pasien tidak ditemukan');
            }

            $fileRequest = $request->validated();

            $fileRequest = array_merge($fileRequest, [
                'register' => $pasien->REGISTER,
                'created_by' => $user->id,
                'created_by_name' => $user->name,
                'created_by_ruangan' => $pasien->RUANGAN,
                'created_by_level' => $user->level,
                'tgl_mrs' => $pasien->TANGGAL,
                'mr' => $pasien->MR,
                'nama' => $pasien->pasien->NAMA,
                'tgl_lahir' => $pasien->pasien->TGL_LAHIR,
            ]);

            $dokumen = Dokumen::create($fileRequest);

            /**
             * UPLOAD FILE
             */
            $result = null;

            if ($request->hasFile('files')) {

                $files = $request->file('files');
                $uploadService = new FileUploadService();
                $filesPayload = [];
                $rules = ['file' => ['required', 'mimes:jpeg,bmp,png,gif,svg,pdf', 'max:4048']];
                foreach ($files as $key => $file) {

                    /**
                     * Validation Image
                     */
                    $validator = Validator::make(['file' => $file], $rules);
                    if ($validator->passes()) {

                        $tglPeriksaRule = str_replace('-', '_', $request->tgl_periksa);

                        $nameRule = str_replace(' ', '_', $request->nama_dok);

                        $penunjang = $request->penunjang;

                        $filename = $dokumen->id . '_' . ++$key . '_' . $pasien->MR . '_' . $nameRule . '_' . $tglPeriksaRule . '_' . $penunjang . '.' . $file->getClientOriginalExtension();

                        $uploadService->setFile($file);
                        $uploadService->setFilename($filename);
                        $uploadService->setDisk('public');
                        $uploadService->setPath('berkas/' . $pasien->MR . '/' . $pasien->REGISTER);
                        $uploadService->setExtension($file->getClientOriginalExtension());
                        $data = $uploadService->upload($request, $pasien, $request->user());
                        $data['id_file'] = $dokumen->id;
                        array_push($filesPayload, $data);
                    }
                    if ($validator->fails()) {
                        throw new \Exception($validator->errors());
                    }

                }

                if (count($filesPayload) > 0) {
                    $result = $uploadService->insertDB($filesPayload);
                } else {
                    throw new \Exception();
                }
            } else {
                throw new \Exception('File Tidak ditemukan');
            }

            DB::commit();

            return $this->createdApiResponse($result, 'Berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorApiResponse($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $dokumens = Dokumen::find($id);

            return $this->okApiResponse($dokumens, 'Berhasil diupdate');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            $dokumens = Dokumen::find($id);
            $dokumens->update($data);

            return $this->okApiResponse($data, 'Berhasil diubah');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $dokumens = Dokumen::find($id);
            $dokumens->delete();

            return $this->noContentApiResponse('Berhasil dihapus');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function registerByRm($mr)
    {
        try {

            $dir = Dokumen::select('register')
                ->getRegistByNoRm($mr)
                ->get();

            return $this->okApiResponse($dir, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
