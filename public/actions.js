$(document).ready(function(){
    $("#cvv").on({
        focus : function(){
            putNumberIn();
            $("#panel").slideToggle();
        },
        keyup : function () {
            document.getElementById('cvv').value = "";
        },
        keydown : function () {
            document.getElementById('cvv').value = "";
        }
    });
    $("button.PCN_CVV").click(function () {
        if ($(this).text() === 'C')
        {
            document.getElementById('cvv').value = "";
            return;
        }
        if(document.getElementById('cvv').value.length < 3) {
            document.getElementById('cvv').value += $(this).text();
        }
        if (document.getElementById('cvv').value.length === 3) {
            $("#panel").slideUp();
        }
        putNumberIn();
    });
});

function putNumberIn() {
    var arr = [0,1,2,3,4,5,6,7,8,9];
    shuffleArray(arr);
    for (let i = 0; i < 10; i++) {
        $('#PCN_CCV_B'+ (i+1)).text(arr[i]);
    }
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}