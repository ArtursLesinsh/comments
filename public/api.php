<?php

include __DIR__ . '/../private/bootstrap.php';

use Storage\DB;        //šeit mes radam ceļu uz mapi, no kurienes ņemt visas metodes ko vājag
use Helpers\Comments; //šeit mes radam ceļu uz mapi, no kurienes ņemt visas metodes ko vājag
use Helpers\Images;  //šeit mes radam ceļu uz mapi, no kurienes ņemt visas metodes ko vājag

header('Content-Type: application/json');//
/*
šeit mes definejām, kā tas ir globāla pieejama funkcija
('Content-Type: application/json') - ar šo mes definejām, kā atbilde bus json formata.
*/
$output = ['status' => false];
if (
    isset($_GET['object']) && is_string($_GET['object']) &&
    isset($_GET['action']) && is_string($_GET['action'])
)
/* 
Šeit tiek padoti 2 nosacijumi:
isset($_GET['object']) && is_string($_GET['object']) - vai $_GET masiva tiek padots objekts, un vai viņš ir 'is_string' tekstuala formata.
isset($_GET['action']) && is_string($_GET['action']) - šeit notiek tās pats, tikai priekš action.
*/
{
    $object_name = $_GET['object'];
    $action_name = $_GET['action'];

    $supported_objects_and_actions = [
        'comment' => ['add', 'update', 'getAll', 'delete', 'get'],
        'image' => ['upload', 'getAll']
    ];
    /*
    Tas ir masivs, kur mēs definejām 2 objektus (comment un image) un piešķiram katram objektam darbibas, ko viņi var izpildit (kas atļauts).
     - comment un image ir atslegas
     - [ ] - tas ir vertibas.
     */

    if (
        array_key_exists($object_name, $supported_objects_and_actions) &&
        in_array($action_name, $supported_objects_and_actions[$object_name])      
    )
    /* 
- array_key_exists($object_name, $supported_objects_and_actions) - ar šo mes parbaudām, vai mainigs($object_name) ir viena no vertibam,
kās satur mainigs ($supported_objects_and_actions) un vai tur ir definetā atslega.
Jā šitas darbibas ir status "True", tad Images.php faila, izpildas "addToDB" funkcija, kura savu laika:

private function addToDB(string $author, string $image_name) {
        return $this->db_images->addEntry([
            'author' => trim($author),
            'file_name' => explode('.', $image_name)[0] - šeit teksts tiek sadalits masiva.
        ]);
    }
- explode - atgriež datus šitada formata:
in_array [
    'getAll''
    'image'
]
Tās ir piemers kā viņš atgriež datus priekš 'images'

- in_array($action_name, $supported_objects_and_actions[$object_name]) - ar šo mes parbaudām, vai mainigs($action_name) ir viena no vertibam,
kās satur mainigs ($supported_objects_and_actions )
- $$action_name - šeit mes parbaudām, vai viņai ir attieciga darbiba.
- $supported_objects_and_actions - tās ir tās, kās palidz atrast ceļu līdz mainigam, kur meklet vertibas.
- $object_name - tās ir comment vai image, ar visiem massiviem kās viņiem ir.
- vai no atbalstamiem objektiem ($supported_objects_and_actions) dabujām objekta nosaukumu [$object_name], un parbaudam,
    vai ($action_name) priekš viņiem eksiste.
- array - masivs.
*/
    {
        $helper = ($object_name == 'comment')
            ? new Comments()
            :  new Images();
        $output = $helper->{$action_name}();
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);

/*
Funkcija - izpilda tikai 1 darbibu, jā bus vairāk, bus jau grutāk viņu saprast, piemerem: 
- funkcija getAll, šeit var visu saprast, kā viņa dabu visu, bet piemeram funkcija:
- getAllSendItToStorage - šeit jau butu grutak saprast ko viņā dara, un izlasit šo funkciju, jo bus sameram gara.
*/