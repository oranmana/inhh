<div class="container row noshow">
  <table class="table table-borderless table-sm">
    <tr class="align-bottom">
      <td id="footleft" min-width=50%>
      @if ($inv->credit)
        <div class="label">Shipping Marks:</div>
        <div>
          <pre style="border:0">{{ $inv->credit->shippingmark}}</pre>
        </div>
      @endif
      </td>
      <td class="align-bottom">
        <div class="docsign">For and on behalf of :</div>
        <div class="small">Joon Woo, Ahn (Mr.)/ASR Sales Manager</div>
      </td>
    </tr>
  </table>
</div>