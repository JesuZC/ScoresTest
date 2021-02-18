<?php
interface ScoreDataIndexerInterface{
    public function getCountOfUsersWithinScoreRange(int $rangeStart, int $rangeEnd):int;
    public function getCountOfUsersByCondition(string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore): int;
}
class readcsv implements ScoreDataIndexerInterface
{
    public array $data;
    public array $head;
    public function getCountOfUsersWithinScoreRange(int $rangeStart, int $rangeEnd):int{
        if($rangeEnd<$rangeStart){echo '<br /><p style="color:#FF0000;">Range start could not be less than range end</p>';return 0;}
        $between = [];
        $count = 0;
        foreach($this->data as $row){
            $eval = intval($row[4],10);
            if($eval>=$rangeStart&&$eval<=$rangeEnd){
                $between[$count]=$row;
                $count+=1;}
        }
        if($count>0){
            echo '<br /><h4>Range: '.$rangeStart.' to '.$rangeEnd.'</h4><br /><table><tr>';
            foreach($this->head as $column){
                echo '<th>'.$column.'</th>';
            }
            echo '</tr>';
            foreach($between as $item){
                echo '<tr>';
                foreach($item as $value){
                    echo '<td>'.$value.'</td>';
                }
                echo '</tr>';
            }
            echo '</table><br /><h6>Count: </h6><p style="color:#0000FF;">'.$count.'</p>';
        }
        return $count;
    }
    public function getCountOfUsersByCondition(string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore): int{
        if(empty($region)||empty($gender)||!isset($hasLegalAge)||!isset($hasPositiveScore)){echo '<br /><p style="color:#FF0000;">No complete data to process</p>';return 0;}
        $count = 0;
        $between = [];
        echo '<br /><h5>Region: '.$region.' Gender: '.$gender.'</h5>';
        if($hasLegalAge){
            if($hasPositiveScore)echo '<p>With legal Age and positve Score</p>';
            else echo '<p>With legal Age and negative Score</p>';
        }else{
            if($hasPositiveScore)echo '<p>Without legal Age and positve Score</p>';
            else echo '<p>Without legal Age and negative Score</p>';
        }
        foreach($this->data as $row){
            $evalRg = array_search($region,$row);
            $evalGn = array_search($gender,$row);
            if($evalRg!==false&&$evalGn!==false){
                if($hasLegalAge){
                    $evalLA = (intval($row[2],10)>=21)?true:false;
                }else{
                    $evalLA = (intval($row[2],10)<21)?true:false;
                }
                if($hasPositiveScore){
                    $evalPS = (intval($row[4],10)>=0)?true:false;
                }else{
                    $evalPS = (intval($row[4],10)<0)?true:false;
                }
                if($evalLA&&$evalPS){
                    $between[$count] = $row;
                    $count += 1;
                }
            }
        }
        if($count>0){
            echo '<br /><h4> Found </h4><br /><table><tr>';
            foreach($this->head as $column){
                echo '<th>'.$column.'</th>';
            }
            echo '</tr>';
            foreach($between as $item){
                echo '<tr>';
                foreach($item as $value){
                    echo '<td>'.$value.'</td>';
                }
                echo '</tr>';
            }
            echo '</table><br />';
        }else{
            echo '<br /><p style="color:#FF0000;">Dont found any coincidence with that criterion</p>';
        }
        echo '<br /><h6>Count: </h6><p style="color:#0000FF;">'.$count.'</p>';
        return $count;
    }
    public function readFile():array{
        if(!file_exists('./scores/scoretab.csv'))echo '<br /><p style="color:#FF0000;">file dont found on /scores/scoretab.csv</p>';
        else{
            $csv = array_map('str_getcsv', file('./scores/scoretab.csv'));
            $fila = 1;
            echo '<table>';
            foreach($csv as $item){
                $list =  preg_split("/[;:,]+/",$item[0]);
                if($fila!==1)$datos[]=$list;
                else $this->head=$list;
                echo '<tr>';
                foreach($list as $value){
                    if($fila===1){
                        echo '<th>'.$value.'</th>';
                    }else{
                        echo '<td>'.$value.'</td>';
                    }
                }
                $fila += 1;
                echo '</tr>';
            }
            echo '</table>';
            return $datos;
        }
        return [];
    }
    public function index(){
        echo '<h2 style="color:#FFC0CB;">HI!!</h2><h5>Welcome to this litle php script which retrieves some data from a .csv file and process it.</h5>';
        $this->data = $this->readFile();
        $this->getCountOfUsersWithinScoreRange(20,50);
        $this->getCountOfUsersWithinScoreRange(-40,0);
        $this->getCountOfUsersWithinScoreRange(0,80);

        $this->getCountOfUsersByCondition('CA', 'w', false, false);
        $this->getCountOfUsersByCondition('CA', 'w', false, true);
        $this->getCountOfUsersByCondition('CA', 'w', true, true);
    }
}
$new = new readcsv();
$new->index();
?>