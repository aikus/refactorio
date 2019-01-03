<?php

class Source
{
    public function method()
    {
        $db = new DbConnection;
        $dbResult = $db->exec("SELECT * FROM my_table WHERE ".
        "data_column like '%some-value%'");
        while($row = $dbResult->fetch())
            {
                $const = 1;
                if($row['field1'] == 100500)
                    {
                        echo $row['field1'].';';
                        $const = 3;
                    }
                echo ($row['rate'] * $const) .';';
                $dataLength = 256 + $const * $row['rate'] + 1;
                $data = substr($row['data_column'], 0, $dataLength);
                echo $data.';';
                echo rand().';';
                echo "\n";
            }
    }
}