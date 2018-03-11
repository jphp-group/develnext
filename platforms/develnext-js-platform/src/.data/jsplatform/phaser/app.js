
MyGame = {
    preload: function () {
        game.load.image('sky', 'assets/sky.png');
        game.load.image('ground', 'assets/platform.png');
        game.load.image('star', 'assets/star.png');
        game.load.spritesheet('dude', 'assets/dude.png', 32, 48);
    },

    create: function () {

    },

    update: function () {

    }
};

game = new Phaser.Game(800, 600, Phaser.AUTO, '', MyGame);

