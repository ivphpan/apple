<?php

/**
 * @var \yii\web\View $this
 * @var \backend\models\apple\Db[] $appleModels
 */

$this->title = "Яблочный Сад";
$createUrl = \yii\helpers\Url::to(['/apple/create']);
$groundUrl = \yii\helpers\Url::to(['/apple/ground']);
$eatUrl = \yii\helpers\Url::to(['/apple/eat']);
$expiredUrl = \yii\helpers\Url::to(['/apple/expired']);
$js = <<<JS
$(function() {
    let tree = $('.tree');
    $("#appleAdd").on("click", function() {
        let color = prompt("Введите цвет яблока:");
        if (color) {
            $.ajax({
                url: "{$createUrl}",
                data: {
                    color: color
                },
                success: function(response){
                    var dateOptions = { year: "numeric", month: "2-digit", day: "2-digit",
                        hour: "2-digit", minute: "2-digit", second: "2-digit"};
                    var ruDateTime = new Intl.DateTimeFormat("ru-RU", dateOptions).format;

                    let appleProt = $(".protList .apple").clone();
                    appleProt.data("id", response.data.id);
                    appleProt.find("._timeCreate").text(ruDateTime(new Date(response.data.datetimeCreate)));
                    appleProt.css("background", response.data.color);
                    appleProt.find("._id").text(response.data.id);
                    appleProt.find("._percent").text(response.data.size);
                    appleProt.find("._isOnTree").removeClass("hide");
                    tree.append(appleProt);
                    consoleWrite("Добавлено яблоко с цветом: " + color + ", id: " + response.data.id, "text-primary");
                },
                method: "POST",
            });
        }
        
        return false;
    });

    let updateData = (container, elem, data) => {
        if (data.isOnTree){
            $("._isOnTree.hide", container).removeClass("hide").siblings().addClass("hide");
        }
        if (data.isEat){
            $("._isEat.hide", container).removeClass("hide").siblings().addClass("hide");
        }
        if (data.isExpires){
            $("._isExpired.hide", container).removeClass("hide").siblings().addClass("hide");
        }
        $("._percent", container).text(Number(data.size).toFixed(2));
    }

    const getFullDate = () => {
        let date = new Date(),
        month = date.getMonth()+1 < 10 ? 'o' + date.getMonth()+1 : date.getMonth()+1,
        minute = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(),
        second = date.getSeconds() >= 0 &&  date.getSeconds() < 10? '0' + date.getSeconds() : date.getSeconds();
        var options = { year: "numeric", month: "2-digit", day: "2-digit",
                        hour: "2-digit", minute: "2-digit", second: "2-digit"};
        var ruDateTime = new Intl.DateTimeFormat("ru-RU", options).format;
        return ruDateTime(date);
    }
    const consoleWrite = (message, className)=>{
        
        message =  getFullDate() + " - " + message;
        $("<li>",{
            text: message,
            class: className
        }).appendTo(".console");
    }

    $(".area").on("click", "._isOnTree, ._isEat, ._isExpired", function(){

        console.log($(this).attr("class") + '  click', $(this));

        let elem = $(this),
            url = elem.data("url"),
            container = elem.parents(".apple"),
            id = container.data("id");
        
        let data = {};

        if (elem.hasClass("_isEat"))
        {
            data.size = prompt("Введите размер порции:");
            if (!data.size){
                return false;
            }
        }

        $.ajax({
            url: url + "?id=" + id,
            data: data,
            method: "POST",
            success: function(response) {
                if (response.data.size==0){
                    container.remove();
                }
                
                if (Object.keys(response.errors).length>0){
                    var errorMessage = '';
                    for (let i in response.errors){
                        errorMessage += response.errors[i].join('<br>');
                    }
                    consoleWrite(errorMessage, "text-danger");
                } else {
                    if (elem.hasClass("_isOnTree")){
                    consoleWrite("Яблоко id: " + id  + " сброшено", "text-primary");
                    }
                    if (elem.hasClass("_isEat")){
                        consoleWrite("Яблоко id: "+id+" откусано: "+data.size+", остаток: "+Number(response.data.size).toFixed(2), "text-primary");
                    }
                    updateData(container, elem, response.data);
                }
            }
        });

        return false;
    });
});
JS;

$css = <<<CSS
.area{
    display:flex;
    flex-direction:column;
    justify-content: space-between;
    height:500px;
}

.tree{
    min-height:150px;
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
}

.apple{
    border-radius:150px;
    width:150px;
    height:150px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    margin-bottom:5px;
}

.apple-info{
    text-align:center;
}

.console-area{
    border:1px solid #ccc;
    padding:10px;
    font-size:10px;
    min-height:80vh;
}
CSS;
$this->registerCss($css);
$this->registerJs($js);
?>

<div class="protList" style="display:none">
    <div class="apple">
        <div class="apple-info">ID: <span class="_id"></span></div>
        <div class="apple-info">Дата выпуска:<br> <span class="_timeCreate"></span></div>
        <div class="apple-size">Остаток: <span class="_percent"></span></div>
        <div class="apple-actions">
            <button class="btn btn-primary hide _isOnTree" data-url="<?= $groundUrl ?>">Сбросить</button>
            <button class="btn btn-info hide _isEat" data-url="<?= $eatUrl ?>">Съесть</button>
            <button class="btn btn-danger hide _isExpired" data-url="<?= $expiredUrl ?>">Удалить</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">

        <div class="area">
            <div class="tree">
                <?php foreach ($appleModels as $apple) : ?>
                    <div class="apple" style="background:<?= $apple->color ?>" data-id="<?= $apple->id ?>">
                        <div class="apple-info">ID: <span class="_id"><?= $apple->id ?></span></div>
                        <div class="apple-info">Дата выпуска:<br> <span class="_timeCreate"><?= date('d.m.Y H:i:s', strtotime($apple->datetimeCreate)) ?></span></div>
                        <div class="apple-size">Остаток: <span class="_percent"><?= $apple->size ?></span></div>
                        <div class="apple-actions">
                            <button class="btn btn-primary <?php if (!$apple->isOnTree) echo 'hide' ?> _isOnTree" data-url="<?= $groundUrl ?>">Сбросить</button>
                            <button class="btn btn-info <?php if (!$apple->isEat) echo 'hide' ?> _isEat" data-url="<?= $eatUrl ?>">Съесть</button>
                            <button class="btn btn-danger <?php if (!$apple->isExpired) echo 'hide' ?> _isExpired" data-url="<?= $expiredUrl ?>">Удалить</button>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <button id="appleAdd" class="btn btn-block btn-primary">Добавить яблоко</button>
        <div class="console-area">
            <ul class="console">
            </ul>
        </div>
    </div>
</div>