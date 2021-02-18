<?php
// src/Controller/ReadCsvController.php
namespace App\Controller;

use App\Entity\SearchByRange;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

interface ScoreDataIndexerInterface{
    public function getCountOfUsersWithinScoreRange(int $rangeStart, int $rangeEnd):int;
    public function getCountOfUsersByCondition(string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore): int;
}
class ReadCsvController extends AbstractController implements ScoreDataIndexerInterface 
{
    public array $data;
    public array $head;
    public array $search = [];
    public array $regions = [];
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
            $this->search = $between;
        }
        return $count;
    }
    public function getCountOfUsersByCondition(string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore): int{
        if(empty($region)||empty($gender)||!isset($hasLegalAge)||!isset($hasPositiveScore)){echo '<br /><p style="color:#FF0000;">No complete data to process</p>';return 0;}
        $count = 0;
        $between = [];
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
            $this->search = $between;
        }
        return $count;
    }
    public function readFile():array{

        $finder = new Finder();
        $pathtoCsv = explode('src',__DIR__);
        if(!file_exists($pathtoCsv[0].'var/scores/scoretab.csv'))echo '<br /><p style="color:#FF0000;">file dont found on /scores/scoretab.csv</p>';
        else{
            $csv = array_map('str_getcsv', file($pathtoCsv[0].'var/scores/scoretab.csv'));
            $fila = 1;
            foreach($csv as $item){
                $list =  preg_split("/[;:,]+/",$item[0]);
                if($fila!==1)$datos[]=$list;
                else $this->head=$list;
                foreach($list as $value){
                    if($fila===1){
                    }else{
                    }
                }
                $fila += 1;
            }
            return $datos;
        }
        return [];
    }
    public function getRegions():array{
        if(count($this->search)>0){
            $evalRegion = array_search('Region',$this->head);
            $regions = [];
            if($evalRegion!==false){
                foreach($this->data as $row){
                    if(count($regions)>0){
                        $evalRep = array_search($row[$evalRegion],$regions);
                        if($evalRep===false&&$evalRep!==0)$regions[]=$row[$evalRegion];
                    }else{
                        $regions[]=$row[$evalRegion];
                    }
                }
            }
            $this->regions=$regions;
        }
        return $this->regions;
    }
    public function getSearch(){
        return $this->search;
    }
    public function index():Response{
        $this->data = $this->readFile();
        $counts = array(); 
        $searchs = array(); 
        $counts[0] = $this->getCountOfUsersWithinScoreRange(20,50);
        $searchs[0] = $this->getSearch();
        $counts[1] = $this->getCountOfUsersWithinScoreRange(-40,0);
        $searchs[1]= $this->getSearch();
        $counts[2] = $this->getCountOfUsersWithinScoreRange(0,80);
        $searchs[2] = $this->getSearch();
        $counts[3] = $this->getCountOfUsersByCondition('CA', 'w', false, false);
        $searchs[3] = $this->getSearch();
        $counts[4] = $this->getCountOfUsersByCondition('CA', 'w', false, true);
        $searchs[4] = $this->getSearch();
        $counts[5] = $this->getCountOfUsersByCondition('CA', 'w', true, true);
        $searchs[5] = $this->getSearch();
        
        $range = new SearchByRange();
        $range->setStartRange(10);
        $range->setEndRange(60);

        $form = $this->createFormBuilder($range)
            ->add('startRange', NumberType::class)
            ->add('endRange', NumberType::class)
            ->add('Search', SubmitType::class, ['label' => 'Search ByRange'])
            ->getForm();
        return $this->render('tableCsv.html.twig',['header'=>$this->head,'data'=>$this->data, 'query'=>$this->search,
                        'regions'=>$this->getRegions(),'form'=>$form->createView(), 'results'=>$searchs,'counts'=>$counts]);
    }
}