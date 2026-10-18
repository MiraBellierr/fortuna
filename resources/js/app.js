import { createApp } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import ApexCharts from 'apexcharts';

// We expose the charting libraries globally
// so that Blade templates can initialise charts.
window.createVueApp = createApp;
window.VueApexCharts = VueApexCharts;
window.ApexCharts = ApexCharts;

console.log('Charts integrated. ApexCharts is now available for Vue apps and plain Blade pages.');

// Chart loading notes: one element per chart, rendered by the chart-note
// Blade component. The helper keeps the note attached across (re)fetches and
// holds the busy state on the chart container.
function chartNote(element) {
    var note = element.__chartNote || element.querySelector('[data-loading]');

    if (!note) {
        // Defensive: the Blade component renders the note, so this only runs
        // if a chart view forgets it.
        note = document.createElement('p');
        note.className = 'absolute inset-0 z-10 flex items-center justify-center text-[13px] text-[#64748B]';
        note.setAttribute('role', 'status');
        note.setAttribute('data-loading', '');
    }

    element.__chartNote = note;

    if (!note.parentNode) {
        element.appendChild(note);
    }

    return note;
}

window.chartNotes = {
    bind: function (element) {
        if (!element) {
            return;
        }

        element.__chartNote = element.querySelector('[data-loading]') || null;
    },

    loading: function (element, text) {
        if (!element) {
            return;
        }

        chartNote(element).textContent = text;
        element.setAttribute('aria-busy', 'true');
    },

    message: function (element, text) {
        if (!element) {
            return;
        }

        chartNote(element).textContent = text;
        element.removeAttribute('aria-busy');
    },

    clear: function (element) {
        if (!element) {
            return;
        }

        var note = element.querySelector('[data-loading]');

        if (note) {
            note.remove();
        }

        element.removeAttribute('aria-busy');
    },
};

// Export links: a plain click is intercepted, the server's response is saved
// via a blob URL, and the link reports its progress in place.
window.exportLinks = {
    bind: function (link, options) {
        if (!link) {
            return;
        }

        options = options || {};

        var error = options.error || null;
        var status = options.status || null;
        var idleLabel = link.textContent.trim();
        var busyLabel = link.getAttribute('data-busy-label') || 'Preparing…';
        var busy = false;

        function setBusy(state) {
            if (state) {
                link.setAttribute('aria-busy', 'true');
                link.setAttribute('aria-disabled', 'true');
                link.style.color = '#94A3B8';
                link.style.pointerEvents = 'none';
                link.textContent = busyLabel;
            } else {
                link.removeAttribute('aria-busy');
                link.removeAttribute('aria-disabled');
                link.style.color = '';
                link.style.pointerEvents = '';
                link.textContent = idleLabel;
            }

            if (status) {
                status.textContent = state ? busyLabel : '';
            }
        }

        link.addEventListener('click', function (event) {
            // Middle-click and modifier clicks keep the plain download.
            if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return;
            }

            event.preventDefault();

            if (busy) {
                return;
            }

            busy = true;
            setBusy(true);

            if (error) {
                error.classList.add('hidden');
            }

            fetch(link.href)
                .then(function (response) {
                    var type = response.headers.get('content-type') || '';

                    if (!response.ok || response.redirected || type.indexOf('text/html') === 0) {
                        throw new Error('Export request failed');
                    }

                    var disposition = response.headers.get('content-disposition') || '';
                    var match = /filename="?([^";]+)"?/i.exec(disposition);

                    return response.blob().then(function (blob) {
                        return { blob: blob, filename: match ? match[1] : (options.filename || 'fortuna-export') };
                    });
                })
                .then(function (download) {
                    var url = URL.createObjectURL(download.blob);
                    var anchor = document.createElement('a');

                    anchor.href = url;
                    anchor.download = download.filename;
                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();

                    // Revoke once the browser has had time to start reading the
                    // blob; revoking immediately can cancel a deferred download.
                    setTimeout(function () {
                        URL.revokeObjectURL(url);
                    }, 5000);

                    busy = false;
                    setBusy(false);
                })
                .catch(function () {
                    busy = false;
                    setBusy(false);

                    if (error) {
                        error.classList.remove('hidden');
                    }
                });
        });
    },
};
