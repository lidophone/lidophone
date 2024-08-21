<?php

namespace App\Services;

use App\Models\Settings;
use DateTimeImmutable;
use DB;

class DailyCompetition
{
    private const QUERY = <<<SQL
SELECT manager, COUNT(manager) AS madeTransfers FROM (
    SELECT uis_employees.full_name AS manager FROM uis_calls uc
    LEFT JOIN uis_employees ON uis_employees.uis_id = uc.first_answered_employee_id
    WHERE uc.status = 1 AND uc.start_time BETWEEN '{date_from}' AND '{date_to}'
    GROUP BY uis_employees.full_name, uc.contact_phone_number, uc.developer_id
    ORDER BY MAX(uc.start_time)
) subquery
GROUP BY manager
ORDER BY madeTransfers DESC
SQL;

    public const TABLE = [
        ['transfersNeeded' => 40, 'award' => '6 000₽', 'color' => '#c280ff', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 35, 'award' => '5 000₽', 'color' => '#8080ff', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 30, 'award' => '4 000₽', 'color' => '#81d3f8', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 25, 'award' => '3 000₽', 'color' => '#80ffff', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 20, 'award' => '2 000₽', 'color' => '#caf982', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 15, 'award' => '1 000₽', 'color' => '#ffff80', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 10, 'award' => '600₽', 'color' => '#facd91', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 5, 'award' => '250₽', 'color' => '#ec808d', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
        ['transfersNeeded' => 1, 'award' => '—', 'color' => '#ffffff', 'manager' => [['fullName' => '—', 'madeTransfers' => 0]]],
    ];

    private DateTimeImmutable $dateTime;
    public readonly array $settings;

    public function __construct()
    {
        $this->dateTime = LIDOFON_DATE_TIME_MOSCOW;
        $this->settings = Settings::whereIn('key', [
            'company_record_manager',
            'company_record_date',
            'company_record_number_of_transfers',
            'company_record_instant_payment',
        ])
        ->get()
        ->toArray();
    }

    private function getData(string $from, string $to, int $limit = 0): array
    {
        $query = str_replace(
            ['{date_from}', '{date_to}'],
            [$from, $to],
            self::QUERY . ($limit ? ' LIMIT ' . $limit : '')
        );
        return DB::select($query);
    }

    public function getDataForToday(): array
    {
        return $this->getData(
            $this->dateTime->format('Y-m-d'),
            $this->dateTime->modify('+1 day')->format('Y-m-d')
        );
    }

    private function getDataForYesterday(): array
    {
        return $this->getData(
            $this->dateTime->modify('-1 day')->format('Y-m-d'),
            $this->dateTime->format('Y-m-d')
        );
    }

    public function getTableForYesterday(): string
    {
        $table = self::TABLE;
        $numberOfRowsInTable = count($table) - 1;

        $data = json_decode(json_encode($this->getDataForYesterday()), true);

        foreach ($table as $rowIndex => $row) {
            foreach ($data as $managerDataIndex => $managerData) {
                if (
                    ($rowIndex === 0 || $rowIndex === $numberOfRowsInTable) &&
                    $managerData['madeTransfers'] >= $row['transfersNeeded']
                ) {
                    $table[$rowIndex]['manager'][] = [
                        'fullName' => $managerData['manager'],
                        'madeTransfers' => $managerData['madeTransfers'],
                    ];
                } elseif (isset($table[$rowIndex + 1])) {
                    if (
                        $managerData['madeTransfers'] >= $table[$rowIndex + 1]['transfersNeeded'] &&
                        $managerData['madeTransfers'] < $row['transfersNeeded']
                    ) {
                        $table[$rowIndex + 1]['manager'][] = [
                            'fullName' => $managerData['manager'],
                            'madeTransfers' =>  $managerData['madeTransfers'],
                        ];
                    }
                }
            }
        }

        $tableBody = '';

        foreach ($table as $row) {

            $managers = '';
            $numberOfManagers = count($row['manager']);
            foreach ($row['manager'] as $index => $manager) {
                if ($numberOfManagers > 1 && $index === 0) {
                    continue;
                }
                $managers .= $manager['fullName'] .
                    ($manager['madeTransfers'] ? ' (' . $manager['madeTransfers'] . ')<br>' : '');
            }

            $tableBody .=
                '<tr>' .
                    '<td style="background:' . $row['color'] . '">' .
                        ($row['transfersNeeded'] === 1 ? '1-4' : $row['transfersNeeded']) .
                    '</td>' .
                    '<td style="background:' . $row['color'] . '">' . $row['award'] . '</td>' .
                    '<td style="background:' . $row['color'] . '">' . $managers . '</td>' .
                '</tr>';
        }

        return $tableBody;
    }
}
