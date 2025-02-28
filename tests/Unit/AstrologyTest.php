<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AstrologyTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        // 메인 실행
        $date = "2025-03-11";
        $time = "12:00 UTC";
        $latitude = 37.5665; // 서울
        $longitude = 126.9780;

        list($planets, $houses) = $this->calculate_positions($date, $time, $latitude, $longitude);
        $aspects = $this->calculate_aspects($planets);
        $result = $this->interpret($date, $planets, $houses, $aspects);

        // 결과 출력
        dump($result);
    }


    // 1. 천체 위치 계산 함수
    function calculate_positions($date, $time, $latitude, $longitude)
    {
        // 줄리안 데이 변환 (실제로는 외부 라이브러리 필요)
        $julian_day = $this->convert_to_julian($date, $time);

        // 모의 데이터: 실제로는 Swiss Ephemeris나 API 호출 필요
        $planets = [
            "Sun"    => ["sign" => "Pisces", "degree" => 20.5],
            "Moon"   => ["sign" => "Libra", "degree" => 10.0],
            "Mars"   => ["sign" => "Leo", "degree" => 15.0],
            "Saturn" => ["sign" => "Pisces", "degree" => 25.0]
        ];

        // 하우스 계산 (모의 데이터, Placidus 시스템 가정)
        $houses = [
            "Sun"    => 10,  // 10하우스
            "Moon"   => 1,  // 1하우스
            "Mars"   => 5,  // 5하우스
            "Saturn" => 10 // 10하우스
        ];

        return [$planets, $houses];
    }

    // 줄리안 데이 변환 (간단 예시, 실제로는 복잡한 계산 필요)
    function convert_to_julian($date, $time)
    {
        $timestamp = strtotime("$date $time");
        return $timestamp / 86400 + 2440587.5; // 대략적인 줄리안 데이 계산
    }

    // 2. 천체 간 각도(Aspects) 계산
    function calculate_aspects($planets)
    {
        $aspects = [];
        $orb = 8; // 허용 오차 8도

        foreach ($planets as $p1 => $data1) {
            foreach ($planets as $p2 => $data2) {
                if ($p1 !== $p2) {
                    $angle = abs($data1["degree"] - $data2["degree"]);
                    $major_aspects = [0, 60, 90, 120, 180];

                    foreach ($major_aspects as $aspect) {
                        if (abs($angle - $aspect) <= $orb) {
                            $aspects[] = ["planet1" => $p1, "planet2" => $p2, "angle" => $aspect];
                        }
                    }
                }
            }
        }

        return $aspects;
    }

    // 3. 해석 데이터베이스 (룩업 테이블)
    function lookup_meaning($planet, $sign, $house)
    {
        $meanings = [
            "Sun"    => [
                "Pisces"  => "Intuition and collective vision are emphasized.",
                "default" => "Identity and vitality shine through."
            ],
            "Moon"   => [
                "Libra"   => "Emotional balance and relationships take focus.",
                "default" => "Emotions and instincts guide actions."
            ],
            "Mars"   => [
                "Leo"     => "Passion and creativity drive bold moves.",
                "default" => "Energy and action are assertive."
            ],
            "Saturn" => [
                "Pisces"  => "Structure meets compassion, testing boundaries.",
                "default" => "Discipline and responsibility shape outcomes."
            ]
        ];

        $house_meanings = [
            1  => "Self and appearance",
            5  => "Creativity and pleasure",
            10 => "Career and public life"
        ];

        $planet_meaning = isset($meanings[$planet][$sign]) ? $meanings[$planet][$sign] : $meanings[$planet]["default"];
        $house_meaning = $house_meanings[$house] ?? "unknown area";

        return "$planet_meaning In house $house: $house_meaning.";
    }

    function lookup_aspect_meaning($aspect)
    {
        $aspect_types = [
            0   => "Conjunction: Unity or tension between energies.",
            60  => "Sextile: Harmony and opportunity.",
            90  => "Square: Challenge and conflict.",
            120 => "Trine: Ease and flow.",
            180 => "Opposition: Polarity and balance needed."
        ];

        return "{$aspect['planet1']} and {$aspect['planet2']} form a {$aspect_types[$aspect['angle']]}";
    }

    // 4. 해석 생성
    function interpret($date, $planets, $houses, $aspects)
    {
        $interpretation = [];

        // 행성과 별자리, 하우스 해석
        foreach ($planets as $planet => $data) {
            $meaning = $this->lookup_meaning($planet, $data["sign"], $houses[$planet]);
            $interpretation[] = "$planet in {$data['sign']} at {$data['degree']} degrees: $meaning";
        }

        // 각도 해석
        foreach ($aspects as $aspect) {
            $interaction = $this->lookup_aspect_meaning($aspect);
            $interpretation[] = $interaction;
        }

        return implode("\n", $interpretation);
    }

}
