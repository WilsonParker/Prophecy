<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Astrology\AstroSeekService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class AstroTest extends TestCase
{
    public function test_guzzle_crawl_data_is_correct(): void
    {
        $url = 'https://horoscopes.astro-seek.com/calculate-monthly-astro-calendar/?narozeni_rok=1900&narozeni_mesic=1&custom_calendar=1&kalendar_quick_planeta_1=&kalendar_quick_aspekt=major&kalendar_quick_luna=';
        $client = new Client();
        $res = $client->get($url);

        $crawler = new Crawler($res->getBody());
        $crawler->filter('table')
                ->first()
                ->filter('tr')
                ->slice(2)
                ->first()
                ->each(function (Crawler $node, $i) {
                    $tdList = $node->filter('td');

                    {
                        $td = $tdList->eq(0);
                        $dateNode = $td->filter('strong');
                        $timeNode = $td->filter('.form-info');

                        $this->assertEquals('Jan 1, 1900', $dateNode->text());
                        $date = Carbon::createFromFormat('M j, Y', $dateNode->text());

                        $year = $date->year;
                        $month = $date->month;
                        $day = $date->day;

                        $this->assertEquals(1900, $year);
                        $this->assertEquals(1, $month);
                        $this->assertEquals(1, $day);

                        $this->assertEquals(', 02:56', $timeNode->text());
                        $time = Carbon::createFromFormat('h:i', Str::of($timeNode->text())->remove(', '));

                        $hour = $time->hour;
                        $minute = $time->minute;

                        $this->assertEquals(2, $hour);
                        $this->assertEquals(56, $minute);
                    }

                    {
                        $td = $tdList->eq(1);
                        $description = $td->text();

                        $this->assertEquals('Mercury conjunction Node', $description);
                    }

                    {
                        $td = $tdList->eq(2);
                        $constellation = Str::of($td->filter('img')->attr('alt'))->trim();
                        $location = $td->text();

                        $this->assertEquals('Sagittarius', $constellation);
                        $this->assertEquals('19°09’', $location);
                    }
                });
    }

    public function test_astro_seek_service_is_correct(): void
    {
        $service = app(AstroSeekService::class);
        $astroData = $service->getMonthlyAstrology(1900, 1);

        $item = $astroData[0];
        $this->assertEquals(1900, $item->getYear());
        $this->assertEquals(1, $item->getMonth());
        $this->assertEquals(1, $item->getDay());
        $this->assertEquals(2, $item->getHour());
        $this->assertEquals(56, $item->getMinute());
        $this->assertEquals('Mercury conjunction Node', $item->getDescription());
        $this->assertEquals('sagittarius', $item->getConstellation());
        $this->assertEquals('19°09’', $item->getLocation());

        $astroData = $service->getMonthlyAstrology(1900, 2);

        $item = $astroData[0];
        $this->assertEquals(1900, $item->getYear());
        $this->assertEquals(2, $item->getMonth());
        $this->assertEquals(1, $item->getDay());
        $this->assertEquals(3, $item->getHour());
        $this->assertEquals(3, $item->getMinute());
        $this->assertEquals('Venus square Pluto', $item->getDescription());
        $this->assertEquals('pisces', $item->getConstellation());
        $this->assertEquals('14°49’', $item->getLocation());
    }

    public function test_a()
    {
        // 날짜 설정 (YYYY-MM-DD)
        $start = "2025-01-19";
        $end = "2025-01-20";

        // 행성 ID 목록 (NASA Horizons 기준)
        $planets = [
            "Mercury" => 199,
            "Venus"   => 299,
            "Earth"   => 399,
            "Mars"    => 499,
            "Jupiter" => 599,
            "Saturn"  => 699,
            "Uranus"  => 799,
            "Neptune" => 899,
        ];

        // 결과 저장 배열
        $results = [];

        foreach ($planets as $name => $id) {
            //            $url = "https://ssd.jpl.nasa.gov/api/horizons.api?format=json&COMMAND='$id'&OBJ_DATA='NO'&MAKE_EPHEM='YES'&EPHEM_TYPE='OBSERVER'&CENTER='500@399'&START_TIME='$start'&STOP_TIME='$end'&STEP_SIZE='1d'&QUANTITIES='1,2,4,20,23,24'";
            $url = "https://ssd.jpl.nasa.gov/api/horizons.api?format=json&COMMAND='$id'&OBJ_DATA='NO'&MAKE_EPHEM='YES'&EPHEM_TYPE='OBSERVER'&CENTER='500@399'&START_TIME='$start'&STOP_TIME='$end'&STEP_SIZE='1d'&QUANTITIES='2,4,20'";

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            // 결과 추출
            if (isset($data['result'])) {
                $results[$name] = $data['result'];
            }
        }

        // 출력
        header('Content-Type: application/json');
        $result = json_encode($results, JSON_PRETTY_PRINT);
        echo $result;
        echo "\n";
        echo $results['Mercury'];
    }

    public function test_parse_astro_data()
    {
        $result = <<<EOD
            *******************************************************************************
             Revised: April 12, 2021             Mercury                            199 / 1
            
             PHYSICAL DATA (updated 2024-Mar-04):
              Vol. Mean Radius (km) =  2439.4+-0.1    Density (g cm^-3)     = 5.427
              Mass x10^23 (kg)      =     3.302       Volume (x10^10 km^3)  = 6.085  
              Sidereal rot. period  =    58.6463 d    Sid. rot. rate (rad/s)= 0.00000124001
              Mean solar day        =   175.9421 d    Core radius (km)      = ~1600 
              Geometric Albedo      =     0.106       Surface emissivity    = 0.77+-0.06
              GM (km^3/s^2)         = 22031.86855     Equatorial radius, Re = 2440.53 km
              GM 1-sigma (km^3/s^2) =                 Mass ratio (Sun/plnt) = 6023682
              Mom. of Inertia       =     0.33        Equ. gravity  m/s^2   = 3.701     
              Atmos. pressure (bar) = < 5x10^-15      Max. angular diam.    = 11.0"   
              Mean Temperature (K)  = 440             Visual mag. V(1,0)    = -0.42 
              Obliquity to orbit[1] =  2.11' +/- 0.1' Hill's sphere rad. Rp = 94.4 
              Sidereal orb. per.    =  0.2408467 y    Mean Orbit vel.  km/s = 47.362 
              Sidereal orb. per.    = 87.969257  d    Escape vel. km/s      =  4.435
                                             Perihelion  Aphelion    Mean
              Solar Constant (W/m^2)         14462       6278        9126
              Maximum Planetary IR (W/m^2)   12700       5500        8000
              Minimum Planetary IR (W/m^2)   6           6           6
            *******************************************************************************
        EOD;

        $data = $this->parseData($result);
        dump($data);
    }

    // 정규 표현식을 사용하여 데이터를 추출하는 함수
    function parseData($text)
    {
        $parsedData = [];

        // 날짜 정보 추출
        preg_match('/Revised:\s+([\w\s,]+)/', $text, $matches);
        $parsedData['revision_date'] = $matches[1] ?? '';

        preg_match('/PHYSICAL DATA \(updated ([\w-]+)\)/', $text, $matches);
        $parsedData['update_date'] = $matches[1] ?? '';

        // 정규 표현식 패턴 (숫자 포함된 key-value 추출)
        $patterns = [
            'mean_radius_km'                => '/Vol\. Mean Radius \(km\) =\s+([\d.+-]+)/',
            'density_g_cm3'                 => '/Density \(g cm\^-3\)\s+=\s+([\d.]+)/',
            'mass_x10_23_kg'                => '/Mass x10\^23 \(kg\)\s+=\s+([\d.]+)/',
            'volume_x10_10_km3'             => '/Volume \(x10\^10 km\^3\)\s+=\s+([\d.]+)/',
            'sidereal_rotation_period_days' => '/Sidereal rot. period\s+=\s+([\d.]+) d/',
            'sidereal_rotation_rate_rad_s'  => '/Sid. rot. rate \(rad\/s\)=\s+([\d.e-]+)/',
            'mean_solar_day_days'           => '/Mean solar day\s+=\s+([\d.]+) d/',
            'core_radius_km'                => '/Core radius \(km\)\s+=\s+~?([\d]+)/',
            'geometric_albedo'              => '/Geometric Albedo\s+=\s+([\d.]+)/',
            'surface_emissivity'            => '/Surface emissivity\s+=\s+([\d.+-]+)/',
            'GM_km3_s2'                     => '/GM \(km\^3\/s\^2\)\s+=\s+([\d.]+)/',
            'equatorial_radius_km'          => '/Equatorial radius, Re =\s+([\d.]+) km/',
            'mass_ratio_sun_planet'         => '/Mass ratio \(Sun\/plnt\) =\s+([\d]+)/',
            'moment_of_inertia'             => '/Mom. of Inertia\s+=\s+([\d.]+)/',
            'equatorial_gravity_m_s2'       => '/Equ. gravity  m\/s\^2\s+=\s+([\d.]+)/',
            'atmospheric_pressure_bar'      => '/Atmos. pressure \(bar\) =\s+([<>]?\s*[\dx^+-]+)/',
            'max_angular_diameter_arcsec'   => '/Max. angular diam.\s+=\s+([\d.]+)"/',
            'mean_temperature_K'            => '/Mean Temperature \(K\)\s+=\s+([\d]+)/',
            'visual_magnitude_V1_0'         => '/Visual mag. V\(1,0\)\s+=\s+([-+\d.]+)/',
            'obliquity_to_orbit_arcmin'     => '/Obliquity to orbit\[1\] =\s+([\d.]+)/',
            'hills_sphere_radius_Rp'        => '/Hill\'s sphere rad. Rp =\s+([\d.]+)/',
            'sidereal_orbital_period_years' => '/Sidereal orb. per.\s+=\s+([\d.]+) y/',
            'sidereal_orbital_period_days'  => '/Sidereal orb. per.\s+=\s+([\d.]+) d/',
            'mean_orbital_velocity_km_s'    => '/Mean Orbit vel.\s+km\/s =\s+([\d.]+)/',
            'escape_velocity_km_s'          => '/Escape vel. km\/s\s+=\s+([\d.]+)/',
        ];

        foreach ($patterns as $key => $pattern) {
            preg_match($pattern, $text, $matches);
            $parsedData[$key] = $matches[1] ?? null;
        }

        // 태양 상수 및 적외선 방출 데이터 추출
        preg_match('/Solar Constant \(W\/m\^2\)\s+([\d]+)\s+([\d]+)\s+([\d]+)/', $text, $matches);
        $parsedData['solar_constant_W_m2'] = [
            'perihelion' => $matches[1] ?? null,
            'aphelion'   => $matches[2] ?? null,
            'mean'       => $matches[3] ?? null,
        ];

        preg_match('/Maximum Planetary IR \(W\/m\^2\)\s+([\d]+)\s+([\d]+)\s+([\d]+)/', $text, $matches);
        $parsedData['max_planetary_IR_W_m2'] = [
            'perihelion' => $matches[1] ?? null,
            'aphelion'   => $matches[2] ?? null,
            'mean'       => $matches[3] ?? null,
        ];

        preg_match('/Minimum Planetary IR \(W\/m\^2\)\s+([\d]+)\s+([\d]+)\s+([\d]+)/', $text, $matches);
        $parsedData['min_planetary_IR_W_m2'] = [
            'perihelion' => $matches[1] ?? null,
            'aphelion'   => $matches[2] ?? null,
            'mean'       => $matches[3] ?? null,
        ];

        return $parsedData;
    }

}
