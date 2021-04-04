<div class="tab-pane{{ ($index === 0) ? ' show active' : '' }}" id="sheet{{ $sheet['meta']['sheet_index'] }}" role="tabpanel" aria-labelledby="for_sheet{{ $sheet['meta']['sheet_index'] }}">



<table class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            @if($fileData->has_headers)
            <th class="header_row_selector" valign="middle">

            </th>
            @endif
            @foreach($sheet['meta']['header_row'] as $colIndex => $headercol)
                <th col="{{ $colIndex }}">{{ $headercol }}</th>
            @endforeach
        </tr>
    </thead>
	<tbody>
        @foreach($sheet['data'] as $rowIndex => $data)
            @if(($sheet['meta']['header_row_index']."" !== $rowIndex."" && $fileData->has_headers) || !$fileData->has_headers)

            <tr row="{{ $rowIndex }}">
                @if($fileData->has_headers)
                <th class="header_row_selector" valign="middle"><input name="sheet{{ $sheet['meta']['sheet_index'] }}_header" file="{{ $fileData->id }}" sheet="{{ $sheet['meta']['sheet_index'] }}" class="select-header-row" value="{{ $rowIndex }}" type="radio"></th>
                @endif
                @foreach($data as $colIndex => $columnText)
                    <td col="{{ $colIndex }}" row="{{ $rowIndex }}">{{ $columnText }}</td>
                @endforeach
            </tr>
            @endif
        @endforeach
	</tbody>
</table>

</div>