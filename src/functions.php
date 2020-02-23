<?php

# Домашнее задание №3
// Задание №3.1
function task1($filename)
{
    if (!file_exists($filename)) {
        throw new Exception("File {$filename} not found!");
    }
    $xmlFile = file_get_contents($filename);
    $xmlObj = new SimpleXMLElement($xmlFile);
?>
    <div style='width:700px; background-color:#f5f5f5;padding:20px;'>
        <h2 style='display:inline-block;'>Purchase order: <?=$xmlObj->attributes()->PurchaseOrderNumber;?></h2>
        <h5 style='float:right;'>Order date: <?=$xmlObj->attributes()->OrderDate?></h5>
        <h3>Adresses:</h3>
        <table border="1" cellspacing="0" cellpadding="5" style="width:99%">
            <?php $i = 0; foreach($xmlObj->Address AS $k => $v): $i++; ?>
                <tr>
                    <td><?=$i;?></td>
                    <td>
                        Type: <?=$v->attributes()->Type;?>,
                        Name: <?=$v->Name;?><br>
                        Country: <?=$v->Country;?>,
                        State: <?=$v->State;?>,
                        City: <?=$v->City;?>,
                        Zip-code: <?=$v->Zip;?>,
                        Street: <?=$v->Street;?>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
        <h3>Delivery notes:</h3>
        <span><?=$xmlObj->DeliveryNotes;?></span>
        <h3>Items:</h3>
        <table border="1" cellspacing="0" cellpadding="5" style="width:99%">
            <tr>
                <th>#</th>
                <th>Part number</th>
                <th>Product name</th>
                <th>Quantity</th>
                <th>USPrice</th>
                <th>ShipDate</th>
                <th>Comment</th>
            </tr>
            <?php for($i = 0; $i < $xmlObj->Items->Item->count(); $i++): ?>
                <tr>
                    <td><?=($i + 1);?></td>
                    <td><?=$xmlObj->Items->Item[$i]->attributes()->PartNumber;?></td>
                    <td><?=$xmlObj->Items->Item[$i]->ProductName;?></td>
                    <td><?=$xmlObj->Items->Item[$i]->Quantity;?></td>
                    <td><?=$xmlObj->Items->Item[$i]->USPrice;?></td>
                    <td><?=$xmlObj->Items->Item[$i]->ShipDate;?></td>
                    <td><?=$xmlObj->Items->Item[$i]->Comment    ;?></td>
                </tr>
            <?php endfor;?>
        </table>
    </div>
<?php
}

# Домашнее задание №3
// Задание №3.2
function task2()
{
    define(ELEMENTS_0_LVL, 5);
    define(ELEMENTS_1_LVL_MIN, 0);
    define(ELEMENTS_1_LVL_MAX, 3);

    // Создаем рандомный массив
    $mainArr = [];
    for ($i = 0; $i < ELEMENTS_0_LVL; $i++) {
        $child_elements = mt_rand(ELEMENTS_1_LVL_MIN, ELEMENTS_1_LVL_MAX);

        for($j = 0; $j < $child_elements; $j++) {
            $child_arr[] = mt_rand(0, 10);
        }

        $mainArr[] = ($child_elements > 1) ? $child_arr : mt_rand(0, 10);
        unset($child_arr);
    }

    // Пишем и читаем json в/из файлы(ов)
    $arrEncoded = json_encode($mainArr);
    file_put_contents('output.json', $arrEncoded);
    $arrReaded = file_get_contents('output.json');
    $arrDecoded = json_decode($arrReaded);

    // Стоит ли менять массив?
    if (rand(0, 1) == 1) {
        // Change the arr
        print 'Меняем массив!<br>';
        $arrIndex = mt_rand(0, ELEMENTS_0_LVL);
        $arrValue = mt_rand(0, 10);
        $arrDecoded[$arrIndex] = $arrValue;
    }

    $arrEncoded2 = json_encode($arrDecoded);
    file_put_contents('output2.json', $arrEncoded2);

    $file1 = file_get_contents('output.json');
    $file2 = file_get_contents('output2.json');

    print "<pre>Массивы:<br>{$file1}<br>{$file2}<br>";

    if ($file1 == $file2){
        print 'Массивы одинаковые!<br>';
    } else {
        print 'Массивы разные!<br>';
        print_r(array_diff(json_decode($file2), json_decode($file1)));
    }
}

# Домашнее задание №3
// Задание №3.3
function task3()
{
    // Создаем рандомный массив
    $mainArr = [];
    for ($i = 0; $i < 50; $i++) {
        $mainArr[] = mt_rand(1, 100);
    }

    $fp = fopen('output3.csv', 'w');
    fputcsv($fp, $mainArr, ';');
    fclose($fp);

    $fp = fopen('output3.csv', 'r');
    $readedArr = fgetcsv($fp, 0, ';');

    $sumEven = 0;
    foreach($readedArr AS $v) {
        if ($v % 2 == 0) $sumEven += $v;
    }

    print "SUM even in CSV: {$sumEven}<br>";
}

# Домашнее задание №3
// Задание №3.4
function task4()
{
    $wikiData = file_get_contents('https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json');
    $wikiDataDecoded = json_decode($wikiData);
    $firstKey = key($wikiDataDecoded->query->pages);
    $pageId = $wikiDataDecoded->query->pages->$firstKey->pageid;
    $title = $wikiDataDecoded->query->pages->$firstKey->title;
    print "Title: {$title}, PageId: {$pageId}";
}
