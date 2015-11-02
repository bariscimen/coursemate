<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Goutte\Client;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(count(null));
        $time1 = time();
        $courses = array();
        $obikas_data = array();
        $client = new Client();
        $obikas = "http://registration.boun.edu.tr";

        $crawler = $client->request('GET', $obikas . '/schedule.htm');

        $tmp = $crawler->filter('option')->each(function ($node) {
            return ($node->extract(array('value'))[0]);
        });

        foreach ($tmp as $value) {
            $obikas_data[$value] = null;
        }

        //dd(key($obikas_data));

        $form = $crawler->selectButton('Go')->form();
        $form['semester'] = key($obikas_data);

        $crawler = $client->submit($form);

        $tmp = $crawler->filter('td > a')->each(function ($node) {
            return ($node->extract(array('href'))[0]);
        });

        foreach ($tmp as $value) {
            $crawler2 = $client->request('GET', $obikas . $value);

            $tmp = $crawler2->filter('body > font > table > tr')->each(function ($node) {
                $tmp2 = $node->filter('td')->each(function ($node2) {
                    return ($node2->text());
                });

                return $tmp2;
            });

            $column_names = $tmp[2];
            //$obikas_data[$tmp[0][1]][$tmp[1][1]];

            foreach (array_slice($tmp, 3) as $value2) {
                $tmp_array = null;
                foreach ($value2 as $key => $cell) {
                    //$obikas_data[$tmp[0][1]][$tmp[1][1]][$value[0]][$column_names[$key]] = trim(trim($cell), chr(0xC2).chr(0xA0));
                    //$tmp_array[$column_names[$key]] = trim(trim($cell), chr(0xC2).chr(0xA0));
                    if ($column_names[$key] == "Code.Sec")
                        $tmp_array['code'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Name")
                        $tmp_array['name'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Instr.")
                        $tmp_array['instructor'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Cr.")
                        $tmp_array['credits'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Ects")
                        $tmp_array['credits'] .= '/' . trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Days")
                        $tmp_array['days'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Hours")
                        $tmp_array['hours'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                    if ($column_names[$key] == "Rooms")
                        $tmp_array['rooms'] = trim(trim($cell), chr(0xC2) . chr(0xA0));
                }
                $courses[] = $tmp_array;
                /*if ($tmp_array['name'] == 'LAB' || $tmp_array['name'] == 'P.S.') {
                    //$tmp_array['code'] = end($courses)['code'] . ' ' . $tmp_array['name'];
                    $tmp_array['code'] = $tmp_array['code'] . ' ' . $tmp_array['name'];
                    $tmp_array['credits'] = '';
                    $tmp_array['name'] = end($courses)['name'];
                    $tmp_psorlab[] = $tmp_array;
                }else{
                    if(count($tmp_psorlab)>0){
                        foreach ($tmp_psorlab as $psorlab) {
                            $courses[] = $psorlab;
                        }
                        $tmp_psorlab = array();
                    }else{
                        $courses[] = $tmp_array;
                    }
                }*/
                /*if($tmp_array['name'] == 'LAB' || $tmp_array['name'] == 'P.S.'){
                    $tmp_psorlab[] = $tmp_array;
                }else{
                    $tmp_end = end($courses);
                    if(count($tmp_psorlab) == 1){
                        $tmp_psorlab[0]['code'] = $tmp_end['code'].' '.$tmp_psorlab[0]['name'];
                        $tmp_psorlab[0]['credits'] = '';
                        $tmp_psorlab[0]['name'] = $tmp_end['name'];
                        $courses[] = $tmp_psorlab[0];
                    }elseif(count($tmp_psorlab) > 1){
                        $i = 1;
                        foreach ($tmp_psorlab as $psorlab) {
                            $psorlab['code'] = $tmp_end['code'].' '.$psorlab['name'].' '.$i++;
                            $psorlab['credits'] = '';
                            $psorlab['name'] = $tmp_end['name'];
                            $courses[] = $psorlab;
                        }
                    }else(count($tmp_psorlab) == 0){
                        $courses[] = $tmp_array;
                    }
                    $tmp_psorlab = null;
                }*/
                //$obikas_data[$tmp[0][1]][$tmp[1][1]][$value[0]] = $tmp_array;
            }
        }

        $data = null;
        $tmp_psorlab = null;
        $tmp_course = null;
        foreach ($courses as $course) {
            if ($course['name'] == 'LAB' || $course['name'] == 'P.S.') {
                $tmp_psorlab[] = $course;
            } else {
                if(count($tmp_psorlab) > 0){
                    $i = 1;
                    foreach ($tmp_psorlab as $psorlab) {
                        $psorlab['code'] = $tmp_course['code'].' '.$psorlab['name'].' '.$i++;
                        $psorlab['credits'] = '';
                        $psorlab['name'] = $tmp_course['name'];
                        $data[] = $psorlab;
                    }
                    $tmp_psorlab = null;
                }
                $data[] = $course;
                $tmp_course = $course;
            }
        }


        file_put_contents(public_path() . '/courses.json', json_encode($data));

        echo ((time() - $time1) / 60) . ' dakika sürdü. Toplam kayıt sayısı: ' . count($courses);
        //$crawler = $crawler->filter('body > table > tbody > tr > td');

        //dd($crawler->text());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
