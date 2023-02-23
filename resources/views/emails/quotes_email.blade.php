<x-mail::message>

<h3> From {{ $start_date }} to {{ $end_date }} </h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
                            <tr>
                                <th align="center" valign="top">Date</th>
                                <th align="center" valign="top">High</th>
                                <th align="center" valign="top">Open</th>
                                <th align="center" valign="top">Low</th>
                                <th align="center" valign="top">Close</th>
                                <th align="center" valign="top">Volume</th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
                            @foreach($prices as $price)
                            <tr>
                                <td align="center" valign="top">{{ isset($price["date"]) ? \Carbon\Carbon::parse($price["date"])->format('Y-m-d') : '' }}</td>
                                <td align="center" valign="top">{{ isset($price["high"]) ? $price["high"] : '' }}</td>
                                <td align="center" valign="top">{{ isset($price["open"]) ? $price["open"] : '' }}</td>
                                <td align="center" valign="top">{{ isset($price["low"]) ? $price["low"] : '' }}</td>
                                <td align="center" valign="top">{{ isset($price["close"]) ? $price["close"] : '' }}</td>
                                <td align="center" valign="top">{{ isset($price["volume"]) ? $price["volume"] : '' }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
