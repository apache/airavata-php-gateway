<?php

if (isset($expContainer))
{
if (sizeof($expContainer) == 0)
{
    if (isset($pageNo) && $pageNo == 1) {
        CommonUtilities::print_warning_message('No results found. Please try again.');
    } else {
        CommonUtilities::print_warning_message('No more results found.');
    }
}
else
{
?>

<div id="re" class="table-responsive">
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Application</th>
            {{--<th>Description</th>--}}
            <th>Resource</th>
            <th>Creation Time</th>
            <th>Status</th>
            <!--                    <select class="form-control select-status">-->
            <!--                        <option value="ALL">Status</option>-->
            <!--                    @foreach( $expStates as $index => $state)-->
            <!--                        <option value="{{ $state }}">{{ $state }}</option>-->
            <!--                    @endforeach-->
            <!--                    </select>-->
            <!--                </th>-->
        </tr>


        <?php
        foreach ($expContainer as $experiment) {
            $description = $experiment['experiment']->description;
            if (strlen($description) > 17) // 17 is arbitrary
            {
                $description = substr($experiment['experiment']->description, 0, 17) . '<span class="text-muted">...</span>';
            }

            $resource  = $experiment['experiment']->resourceHostId;
            if(!empty($resource)){
                $resource = explode("_", $resource)[0];
            }else{
                $resource = "";
            }

            echo '<tr>';
            $addEditOption = "";
            if ($experiment['expValue']['editable'])
                $addEditOption = '<a href="' . URL::to('/') . '/experiment/edit?expId=' . $experiment['experiment']->experimentId . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';

            echo '<td>' . $experiment['experiment']->name . $addEditOption . '</td>';

            echo '<td>' . $experiment['expValue']['applicationInterface']->applicationName . '</td>';

            echo '<td>' . $resource . '</td>';

            //echo "<td>$computeResource->hostName</td>";
            echo '<td class="time" unix-time="' . $experiment['experiment']->creationTime / 1000 . '"></td>';


            switch ($experiment['expValue']['experimentStatusString']) {
                case 'CANCELING':
                case 'CANCELED':
                case 'UNKNOWN':
                    $textClass = 'text-warning';
                    break;
                case 'FAILED':
                    $textClass = 'text-danger';
                    break;
                case 'COMPLETED':
                    $textClass = 'text-success';
                    break;
                default:
                    $textClass = 'text-info';
                    break;
            }

            ?>
            <td>
                <a class="<?php echo $textClass; ?>"
                   href="{{ URL::to('/') }}/experiment/summary?expId=<?php echo $experiment['experiment']->experimentId; ?>">
                    <?php echo $experiment['expValue']['experimentStatusString']; ?>
                </a>
            </td>

            </tr>

        <?php
        }
        }
        }
        ?>
    </table>
</div>