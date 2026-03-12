document.addEventListener('alpine:init', () => {
    Alpine.data('streamTarget', () => ({
        observer: null,

        init() {
            this.observer = new MutationObserver(() => {
                this.$el
                    .closest('#ai-chat-messages')
                    ?.scrollTo({ top: this.$el.closest('#ai-chat-messages').scrollHeight, behavior: 'smooth' });
            });

            this.observer.observe(this.$el, {
                childList: true,
                characterData: true,
                subtree: true,
            });
        },

        destroy() {
            this.observer?.disconnect();
        },
    }));
});
