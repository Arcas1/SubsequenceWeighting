<?php

/**
 * Description of SubsequenceWeighting
 *
 * @author williamdaza
 */
$post = (object) $_POST;
index($post);

function index($post) {
    echo '<pre>';
    $data = getInputs($post->subsequence);
    $maxSum = 0;
    for ($i = 0; $i < $data['size']; $i++) {
        $maxSum = findMaxSubsequenceSum($data['data'][$i]);
        echo $maxSum . '<br>';
    }
}

function findMaxSubsequenceSum($data) {
    $map = getMap($data);
    $lists = $map;
    for ($i = 0; $i < count($map); $i++) {
        $index[] = 0;
    }
    //Ok max index 3 -> 4, 4, 7
    $maxSum = getMaxSumInSequence($lists, $index);
    return $maxSum;
}

function getMaxSumInSequence($lists, $index) {
    
    //List 
    $result = 0;
    $maxSum = 0;
    $indexUpdate = true;
    $maxListLength = count($lists);
    var_dump($lists);
    
    while (isValidSequence($index) && $indexUpdate) {
        $result = addAllListItem($lists, $index);
        if ($maxSum < $result) {
            $maxSum = $result;
        }
        $return = updateIndex($maxListLength, $index);
        $indexUpdate = $return['result'];
        $index = $return['index'];
    }
    return $maxSum;
}

function addAllListItem($lists, $index = []) {
    $result = 0;
    
    for ($i = 1; $i < count($lists); $i++) {
        $result += $lists[$index[$i]];
    }
    return $result;
}

function updateIndex($maxIndex, $index = []) {
    $result = false;

    if ($index[0] == $maxIndex - 1) {
        return ['result' => $result, 'index' => $index];
    }

    $indexSize = count($index) - 1;
    if ($index[0] == $index[$indexSize]) {
        $index[$indexSize] ++;
        $result = true;
    } else {
        for ($i = $indexSize; $i > 0; $i--) {
            $indexValue = $index[$i];
            if ($indexValue > $index[$i - 1]) {
                $index[$i - 1] ++;
                $result = true;
                break;
            }
        }
    }
    return ['result' => $result, 'index' => $index];
}

function isValidSequence($index) {
    $i = $index[0];
    $result = false;
    for ($l = 1; $l < count($index); $l++) {
        if ($i <= $index[$l]) {
            $result = true;
        } else {
            $result = false;
        }
        $i = $index[$l];
    }
    return $result;
}

function getMap($array = []) {
    $map = [];
    $list = [];
    for ($i = 0; $i < count($array['value']); $i++) {
        $key = $array['value'][$i];
        $item = $array['weight'][$i];
        if (isset($map[$key])) {
            $map[$key] = $item;
        } else {
            $list[] = $item;
            if (isset($map[$key])) {
                $map[$key] = $item;
            } else {
                $map[] = $item;
            }
        }
    }
    return $map;
}

function getInputs($subsequence) {
    $initialInputs = explode("\n", $subsequence);
    $arraySize = (int) $initialInputs[0];
    unset($initialInputs[0]);
    $subsequenceArrays = array_chunk($initialInputs, $arraySize);
    $data = [];
    for ($i = 0; $i < $arraySize; $i++) {
        $firstGroup = explode(' ', trim($subsequenceArrays[$i][1]));
        $secondGroup = explode(' ', trim($subsequenceArrays[$i][2]));
        $data[] = [
            'value' => $firstGroup,
            'weight' => $secondGroup
        ];
    }
    return ['data' => $data, 'size' => $arraySize];
}
