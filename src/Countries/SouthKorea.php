<?php

namespace Spatie\Holidays\Countries;

use Carbon\CarbonImmutable;

class SouthKorea extends Country
{
    public function countryCode(): string
    {
        return 'kr';
    }

    /** @return array<string, CarbonImmutable> */
    protected function allHolidays(int $year): array
    {
        return array_merge([
            '신정' => '01-01',
            '삼일절' => '03-01',
            '어린이날' => '05-05',
            '현충일' => '06-06',
            '광복절' => '08-15',
            '개천절' => '10-03',
            '한글날' => '10-09',
            '크리스마스' => '12-25',
        ], $this->variableHolidays($year));
    }

    /** @return array<string, CarbonImmutable> */
    protected function variableHolidays(int $year): array
    {
        $lunarNewYear = $this->convertToLunar($year, 1, 1);
        $buddhasBirthday = $this->convertToLunar($year, 4, 8);
        $chuseok = $this->convertToLunar($year, 8, 15);

        return [
            '설날' => CarbonImmutable::createFromFormat('Y-m-d', $lunarNewYear)->setTimezone('Asia/Seoul'),
            '부처님 오신 날' => CarbonImmutable::createFromFormat('Y-m-d', $buddhasBirthday)->setTimezone('Asia/Seoul'),
            '추석' => CarbonImmutable::createFromFormat('Y-m-d', $chuseok)->setTimezone('Asia/Seoul'),
        ];
    }

    protected function convertToLunar(int $year, int $month, int $day): string
    {
        // 그레고리력 날짜를 율리우스 적일로 변환
        $julianDay = unixtojd(mktime(0, 0, 0, $month, $day, $year));

        // 율리우스 적일을 음력 날짜로 변환
        $lunar = jdtokorean($julianDay, true);

        // 음력 날짜를 "Y-m-d" 형식으로 반환
        list($lunarYear, $lunarMonth, $lunarDay) = explode('-', $lunar);

        return "$lunarYear-$lunarMonth-$lunarDay";
    }
}
