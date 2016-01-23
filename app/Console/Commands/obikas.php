<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Goutte\Client;

class obikas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'obikas:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches OBIKAS schedules.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	$this->info("Welcome to OBIKAS COurse List Crawler\n");
        $time1 = time();
        $courses = array();
        $obikas_data = array();
        $client = new Client();
        $obikas = "http://registration.boun.edu.tr";

        $crawler = $client->request('GET', $obikas . '/schedule.htm');

        $tmp = $crawler->filter('option')->each(function ($node) {
            return ($node->extract(array('value'))[0]);
        });

	$this->info("OBIKAS Semester List:");

        foreach ($tmp as $key =>  $value) {
            echo $key." => ".$value."\n" ;
        }

	do{
		$selected_semester = $this->ask('Please enter the semester number: ');
		if(($selected_semester != 0 && empty($selected_semester)) || !isset($tmp[$selected_semester]))
			$this->info("Wrong semester number. ");
	}while(($selected_semester != 0 && empty($selected_semester)) || !isset($tmp[$selected_semester]));
        //dd(($tmp));

        $form = $crawler->selectButton('Go')->form();
        $form['semester'] = $tmp[$selected_semester];
	
	echo "\nGetting department list...";
        $crawler = $client->submit($form);
	echo "DONE\n";

        $tmp = $crawler->filter('td > a')->each(function ($node) {
            return ($node->extract(array('href'))[0]);
        });

	echo "\nGetting course list";
        foreach ($tmp as $value) {
	    echo ".";
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

	$this->info("\n\nGetting course list is DONE.");
        file_put_contents(public_path() . '/courses.json', json_encode($data));
        $this->info("Course list saved to " . public_path() . '/courses.json');
	$this->info("\nTotal Course Count: ".count($courses).", Execution Time: ".round(((time() - $time1) / 60), 2)." minutes.");
    }
}
