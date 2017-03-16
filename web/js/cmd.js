$(function () {
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.post($('form').attr('action'), $('input').val(), function (data) {
            var p = $('<p>').append('<mark>' + data.name + '</mark><br>');
            if (data.text) {
                p.append('<sample>' + data.text + '</sample><br>');
            };
            if (data.hand) {
                $.each(data.hand, function () {
                    p.append('<div class="card ' + this.suit + ' ' + this.value + '">');
                });
            }
            $('.session').prepend(p);
            if (data.trick) {
                $('.trick .card').removeClass().addClass("card open");
                if (data.trick.turns) {
                    $.each(data.trick.turns, function() {
                        $('[data-name="' + this.player.name + '"] .card').removeClass("open").addClass(this.card.suit + " " + this.card.value);
                    })
                }
            }
            if (data.player) {
                $('.card.current').removeClass("current");
                $('[data-name="' + data.player.name + '"] .card').addClass("current");
            }
            $('input').val('').focus();
        });
    });
});