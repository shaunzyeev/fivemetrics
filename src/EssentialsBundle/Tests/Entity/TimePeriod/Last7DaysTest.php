<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/25/17
 * Time: 8:53 AM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\Last7Days;

class Last7DaysTest extends TimePeriodTest
{
    protected function setUp()
    {
        $this->timePeriod = new Last7Days();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodLast7Days class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLast31Days()
    {
        $this->assertInstanceOf(Last7Days::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Last7Days class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Last7Days();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $date->modify("-7 day");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Last7Days class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Last7Days();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $date->modify("-7 day");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }
}
