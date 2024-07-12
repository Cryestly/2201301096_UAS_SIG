<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {

        $petas = $this->petaModel->findAll();

        $features = [];
        $dataPeta = [];
$geoJson = [];
        foreach ($petas as $key) {
            $gis = $this->gisModel->where('id_peta', $key['id_peta'])->findAll();
            foreach ($gis as $row) {
                $name = $row['name'];
                $coordinates = json_decode($row['coordinat']);
                $type = $row['type'];

                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'name' => $name,
                        //sesuaikan 'Choice' => $name
                    ],
                    'geometry' => [
                        'coordinates' => $coordinates,
                        'type' => $type,
                    ],
                    'id' => count($features),
                ];

                array_push($features, $feature);
            }
            $geoJson = [
                'type' => 'FeatureCollection',
                'features' => $features,
            ];

            $dataPeta[] = [
                'nama_peta' => $key['nama_peta'],
                'id_peta' => $key['id_peta'],
                'geoJson' => json_encode($geoJson),
            ];

        }
        $data = [
            'peta' => $dataPeta,
            'geoJson' => json_encode($geoJson),
        ];
        return view('index', $data);
    }
    public function detail($id): string
    {

        $petas = $this->petaModel->where('id_peta', $id)->findAll();

        $features = [];
        $dataPeta = [];
        $i = 0;
        foreach ($petas as $key) {
            $gis = $this->gisModel->where('id_peta', $key['id_peta'])->findAll();
            foreach ($gis as $row) {
                $name = $row['name'];
                $coordinates = json_decode($row['coordinat']);
                $type = $row['type'];

                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'name' => $name,
                        //sesuaikan 'Choice' => $name
                    ],
                    'geometry' => [
                        'coordinates' => $coordinates,
                        'type' => $type,
                    ],
                    'id' => count($features),
                ];

                array_push($features, $feature);
            }
            $geoJson = [
                'type' => 'FeatureCollection',
                'features' => $features,
            ];

            $dataPeta = [
                'nama_peta' => $key['nama_peta'],
                'id_peta' => $key['id_peta'],
                'geoJson' => json_encode($geoJson),

            ];

        }
        return view('detail', $dataPeta);
    }

    public function tambahData()
    {
        $dataPost = $this->request->getVar();
        $dataPeta = [
            'nama_peta' => $dataPost['nama-peta'],
        ];

        $this->petaModel->save($dataPeta);

        $idPeta = $this->petaModel->orderBy('id_peta', 'DESC')->first();
        $geoJson = $this->request->getPost('geojson');
        $data = json_decode($dataPost['geojson']);

        // Akses properti dari objek dengan operator ->
        foreach ($data->features as $feature) {
            $name = $feature->properties->name;
            $coordinates = json_encode($feature->geometry->coordinates);
            $type = $feature->geometry->type;
            $dataToSave = [
                'name' => $name,
                'coordinat' => $coordinates,
                'type' => $type,
                'id_peta' => $idPeta['id_peta'],
            ];
            $this->gisModel->save($dataToSave);
        }
        session()->setFlashData('success', 'Data Berhasil disimpan');
        return redirect()->to('/');
    }

    public function edit()
{
    $dataPost = $this->request->getVar();
    $dataPeta = [
        'nama_peta' => $dataPost['nama-peta'],
    ];

    // Update data peta
    $this->petaModel->update($dataPost['id'], $dataPeta);
    
    // Hapus data GIS yang ada untuk peta ini
    $this->gisModel->where('id_peta', $dataPost['id'])->delete();

    // Ambil GeoJSON dari request
    $geoJson = $this->request->getPost('geojson');
    $data = json_decode($geoJson);

    // Cek jika json_decode gagal
    if (json_last_error() !== JSON_ERROR_NONE) {
        session()->setFlashData('error', 'GeoJSON tidak valid: ' . json_last_error_msg());
        return redirect()->to('/');
    }

    // Pastikan features ada dan merupakan array
    if (isset($data->features) && is_array($data->features)) {
        foreach ($data->features as $feature) {
            if (isset($feature->properties->name) && isset($feature->geometry)) {
                $name = $feature->properties->name;
                $coordinates = json_encode($feature->geometry->coordinates);
                $type = $feature->geometry->type;

                $dataToSave = [
                    'name' => $name,
                    'coordinat' => $coordinates,
                    'type' => $type,
                    'id_peta' => $dataPost['id'],
                ];

                // Simpan data GIS
                $this->gisModel->save($dataToSave);
            } else {
                log_message('error', 'Feature tidak memiliki properti yang diperlukan: ' . json_encode($feature));
            }
        }
    } else {
        log_message('error', 'Tidak ada features dalam GeoJSON.');
        session()->setFlashData('error', 'GeoJSON tidak memiliki features.');
    }

    session()->setFlashData('success', 'Data Berhasil diubah');
    return redirect()->to('/');
}

    public function delete($id)
    {
        $this->gisModel->where('id_peta', $id)->delete();
        $this->petaModel->delete($id);
        session()->setFlashData('success', 'Data Berhasil dihapus');
        return redirect()->to('/');
    }

}
