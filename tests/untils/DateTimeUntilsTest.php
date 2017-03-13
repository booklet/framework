<?php
class DateTimeUntilsTest extends TesterCase
{
    public function testMonthBeginningDate()
    {
        $month_beginning_date = DateTimeUntils::monthBeginningDate(2017, 1);

        Assert::expect($month_beginning_date)->to_equal('2017-01-01 00:00:00');

        $month_beginning_date = DateTimeUntils::monthBeginningDate(2016, 12);

        Assert::expect($month_beginning_date)->to_equal('2016-12-01 00:00:00');
    }

    public function testMonthEndDate()
    {
        $month_beginning_date = DateTimeUntils::monthEndDate(2017, 1);

        Assert::expect($month_beginning_date)->to_equal('2017-01-31 23:59:59');

        $month_beginning_date = DateTimeUntils::monthEndDate(2017, 2);

        Assert::expect($month_beginning_date)->to_equal('2017-02-28 23:59:59');
    }
}
