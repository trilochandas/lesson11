{literal}
<style>
table{
    border: 1px;
    border-collapse: collapse;
    text-align: center;
}
td{
    border: 1px solid;
    padding: 10px;
}
form{width: 500px;}
input:not([type="radio"]), select, textarea {
    float: right;
}
input{
    margin: 3px 0;
}
input[type='text'], select, textarea{
    width: 175px;
}
.form-row {
    margin: 10px 0;
    clear: both;
}

</style>
{/literal}

<p style="color:red;">{$error}</p>
<form  method="POST">
    <div class="form-row-indented"> 
        <label><input type="radio" {if $private == 1} {'checked'} {/if}  value="1" name="private">Частное лицо</label> 
        <label><input type="radio" {if $private == 0} {'checked'} {/if} value="0" name="private">Компания</label> 
    </div>
    <div class="form-row"> 
        <label for="fld_seller_name"><b> Ваше имя</b></label>
        <input type="text" maxlength="40" value="{$seller_name}" name="seller_name" id="fld_seller_name">
    </div>
    <div class="form-row"> 
        <label for="fld_email">Электронная почта</label>
        <input type="text" class="form-input-text" value="{$email}" name="email" id="fld_email">
    </div>
    <br>
    <div class="form-row-indented"> 
        <label  for="allow_mails">
        <input type="checkbox" {$allow_mails} value="1" name="allow_mails" id="allow_mails" ><span>Я не хочу получать вопросы по объявлению по e-mail</span>
        </label> 
    </div>
    <div class="form-row"> 
        <label for="fld_phone" class="form-label">Номер телефона</label>
        <input type="text"  value="{$phone}" name="phone" id="fld_phone">
    </div>
    <div id="f_location_id" class="form-row "> 
    <label for="region" class="form-label">Город</label> 
        {html_options name=city options=$citys selected=$city }
    </div>
    <div id="f_metro_id" class="form-row "> 
        <label for="metro_id" class="form-label">Метро</label>
        {html_options name=metro options=$metro1 selected=$metro }
    </div>
    <!-- <div class="form-row "> 
        <label for="category_id" >Категория</label>
        {html_options name=category_id options=$categorys selected=$category_id }
    </div> -->
    <div id="f_title" class="form-row f_title"> 
        <label for="fld_title" >Название объявления</label> 
        <input maxlength="50" value="{$title}" name="title" id="fld_title" type="text"> 
    </div>
    <div class="form-row"> 
        <label for="fld_description">Описание объявления</label> 
        <textarea maxlength="3000" name="description" id="fld_description" >{$description}</textarea> 
    </div>
    <div id="price_rw" class="form-row rl"> 
        <label for="fld_price" >Цена</label> 
        <input maxlength="9" value="{$price}" placeholder="0" name="price" id="fld_price" type="text">&nbsp;<span>руб.</span>
    </div>
    <div>
        <input type="submit" value="Далее" name="main_form_submit" >
    </div>
    <br>
    <br>
    </form>   
    <h2>Все объявления</h2>
    <table>
        <tr>
            <td>Название объявления</td>
            <td>Цена</td>
            <td>Имя продавца</td>
            <td>Удалить</td>
        </tr>

    {foreach from=$advert_output_table key=k item=v}
        <tr>
            <td><a href="{$smarty.server.SCRIPT_NAME}?id={$v.id}">{$v.title}</a></td>
            <td>{$v.price}</td> 
            <td>{$v.seller_name}</td> 
            <td><a href="{$smarty.server.SCRIPT_NAME}?del={$v.id}">Удалить</a></td> 
       </tr>
    {/foreach}
    </table>
