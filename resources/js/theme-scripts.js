// All scripts to go inside this listener.
document.addEventListener("DOMContentLoaded", () => {
    /**
     * Listen to tab events to enable outlines only when needed.
     * (accessibility improvement)
     **/
    document.body.addEventListener("keyup", function(e) {
        if (e.which === 9) {
            /* tab */ document.documentElement.classList.remove(
                "no-focus-outline"
            )
        }
    })
})