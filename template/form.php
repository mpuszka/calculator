<%message%>
<form method="POST" class="calculator-front-form">
    <%nonce%>
    <div class="form-group">
        <label for="title">Nazwa produktu:</label>
        <input id="title" class="form-control" type="text" name="title" value="" required>
    </div>
    <div class="form-group">
        <label for="net-price">Kwota netto:</label>
        <input id="net-price" class="form-control" type="number" name="net-price" value="" required>
    </div>
    <div class="form-group">
        <label for="currency">Waluta:</label>
        <input id="currency" class="form-control" type="text" name="currency" value="PLN" readonly>
    </div>
    <div class="form-group">
        <label for="vat">Stawka VAT:</label>
        <select name="vat" class="form-control" required>
            <option value="">Select</option>
            <option value="23">23%</option>
            <option value="22">22%</option>
            <option value="8">8%</option>
            <option value="7">7%</option>
            <option value="5">5%</option>
            <option value="3">3%</option>
            <option value="0">0%</option>
            <option value="zw">zw</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Oblicz</button>
</form>
