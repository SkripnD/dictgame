if (!RedactorPlugins) var RedactorPlugins = {};
 
RedactorPlugins.bufferbuttons = function() {
    return {
        init: function() {
            var undo = this.button.addFirst('undo', 'Отменить');
            var redo = this.button.addAfter('undo', 'redo', 'Вернуть');
 
            this.button.addCallback(undo, this.buffer.undo);
            this.button.addCallback(redo, this.buffer.redo);
        }
    };
};