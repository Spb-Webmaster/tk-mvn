(function (window, document) {
  'use strict';

  const defaults = {
    opacity: 0.5,
    inDuration: 250,
    outDuration: 250,
    onOpenStart: null,
    onOpenEnd: null,
    onCloseStart: null,
    onCloseEnd: null,
    preventScrolling: true,
    dismissible: true,
    startingTop: '4%',
    endingTop: '10%'
  };

  const instanceKey = 'M_Modal';
  const triggerSelector = '.modal-trigger';
  const closeSelector = '.modal-close';
  let modalCount = 0;
  let modalsOpen = 0;
  let bodyOverflowBeforeModal = '';

  const M = window.M || (window.M = {});

  M.getIdFromTrigger = function (trigger) {
    let id = trigger.getAttribute('data-target');

    if (!id) {
      const href = trigger.getAttribute('href');
      const hashIndex = href ? href.indexOf('#') : -1;
      id = hashIndex >= 0 ? href.slice(hashIndex + 1) : '';
    }

    return id;
  };

  class Modal {
    constructor(el, options) {
      if (!(el instanceof Element)) {
        throw new TypeError('Modal expects an HTML element.');
      }

      const oldInstance = Modal.getInstance(el);
      if (oldInstance) {
        oldInstance.destroy();
      }

      this.el = el;
      this.options = Object.assign({}, Modal.defaults, options);
      this.isOpen = false;
      this.id = el.id;
      this._openingTrigger = undefined;
      this._nthModalOpened = 0;
      this._animationTimer = null;
      this._overlay = document.createElement('div');
      this._hadTabIndex = this.el.hasAttribute('tabindex');

      this._overlay.className = 'modal-overlay';
      this.el[instanceKey] = this;
      this.el.setAttribute('aria-hidden', 'true');

      if (!this.el.hasAttribute('tabindex')) {
        this.el.tabIndex = 0;
      }

      modalCount += 1;
      this._setupEventHandlers();
    }

    static get defaults() {
      return defaults;
    }

    static init(els, options) {
      if (els instanceof Element) {
        return new Modal(els, options);
      }

      if (typeof els === 'string') {
        els = document.querySelectorAll(els);
      }

      if (els && typeof els.length === 'number') {
        return Array.from(els).map(function (el) {
          return new Modal(el, options);
        });
      }

      return null;
    }

    static getInstance(el) {
      return el ? el[instanceKey] : undefined;
    }

    destroy() {
      if (this.isOpen) {
        this.isOpen = false;
        modalsOpen = Math.max(0, modalsOpen - 1);
        this._removeDismissibleHandlers();
      }

      modalCount = Math.max(0, modalCount - 1);
      this._removeEventHandlers();
      this._clearAnimationTimer();
      this._overlay.remove();
      this.el.classList.remove('open');
      this.el.removeAttribute('style');
      this.el.removeAttribute('aria-hidden');
      if (!this._hadTabIndex) {
        this.el.removeAttribute('tabindex');
      }
      this.el[instanceKey] = undefined;

      if (modalsOpen === 0) {
        document.body.style.overflow = bodyOverflowBeforeModal;
        bodyOverflowBeforeModal = '';
      }
    }

    open(trigger) {
      if (this.isOpen) {
        return this;
      }

      this.isOpen = true;
      modalsOpen += 1;
      this._nthModalOpened = modalsOpen;
      this._openingTrigger = trigger instanceof Element ? trigger : undefined;
      this._overlay.style.zIndex = 1000 + modalsOpen * 2;
      this.el.style.zIndex = 1000 + modalsOpen * 2 + 1;

      if (typeof this.options.onOpenStart === 'function') {
        this.options.onOpenStart.call(this, this.el, this._openingTrigger);
      }

      if (this.options.preventScrolling) {
        if (modalsOpen === 1) {
          bodyOverflowBeforeModal = document.body.style.overflow;
        }
        document.body.style.overflow = 'hidden';
      }

      this.el.classList.add('open');
      this.el.setAttribute('aria-hidden', 'false');
      this.el.insertAdjacentElement('afterend', this._overlay);

      if (this.options.dismissible) {
        this._addDismissibleHandlers();
      }

      this._animateIn();
      this.el.focus({ preventScroll: true });

      return this;
    }

    close() {
      if (!this.isOpen) {
        return this;
      }

      this.isOpen = false;
      modalsOpen = Math.max(0, modalsOpen - 1);
      this._nthModalOpened = 0;

      if (typeof this.options.onCloseStart === 'function') {
        this.options.onCloseStart.call(this, this.el);
      }

      this.el.classList.remove('open');
      this.el.setAttribute('aria-hidden', 'true');
      this._removeDismissibleHandlers();

      if (modalsOpen === 0) {
        document.body.style.overflow = bodyOverflowBeforeModal;
        bodyOverflowBeforeModal = '';
      }

      this._animateOut();

      return this;
    }

    _setupEventHandlers() {
      this._handleOverlayClickBound = this._handleOverlayClick.bind(this);
      this._handleModalCloseClickBound = this._handleModalCloseClick.bind(this);
      this._overlay.addEventListener('click', this._handleOverlayClickBound);
      this.el.addEventListener('click', this._handleModalCloseClickBound);

      if (modalCount === 1) {
        document.body.addEventListener('click', handleTriggerClick);
      }
    }

    _removeEventHandlers() {
      this._overlay.removeEventListener('click', this._handleOverlayClickBound);
      this.el.removeEventListener('click', this._handleModalCloseClickBound);

      if (modalCount === 0) {
        document.body.removeEventListener('click', handleTriggerClick);
      }
    }

    _addDismissibleHandlers() {
      this._handleKeydownBound = this._handleKeydown.bind(this);
      this._handleFocusBound = this._handleFocus.bind(this);
      document.addEventListener('keydown', this._handleKeydownBound);
      document.addEventListener('focus', this._handleFocusBound, true);
    }

    _removeDismissibleHandlers() {
      if (this._handleKeydownBound) {
        document.removeEventListener('keydown', this._handleKeydownBound);
        this._handleKeydownBound = null;
      }

      if (this._handleFocusBound) {
        document.removeEventListener('focus', this._handleFocusBound, true);
        this._handleFocusBound = null;
      }
    }

    _handleOverlayClick() {
      if (this.options.dismissible) {
        this.close();
      }
    }

    _handleModalCloseClick(event) {
      const closeTrigger = event.target.closest(closeSelector);

      if (closeTrigger && this.el.contains(closeTrigger)) {
        event.preventDefault();
        this.close();
      }
    }

    _handleKeydown(event) {
      if (event.key === 'Escape' && this.options.dismissible) {
        this.close();
      }
    }

    _handleFocus(event) {
      if (!this.el.contains(event.target) && this._nthModalOpened === modalsOpen) {
        this.el.focus({ preventScroll: true });
      }
    }

    _animateIn() {
      const duration = Number(this.options.inDuration) || 0;

      this._clearAnimationTimer();
      this._setTransition(duration);
      this._overlay.style.display = 'block';
      this._overlay.style.opacity = '0';
      this.el.style.display = 'block';
      this.el.style.opacity = '0';

      if (this._isBottomSheet()) {
        this.el.style.bottom = '-100%';
        this.el.style.transform = 'none';
      } else {
        this.el.style.top = this.options.startingTop;
        this.el.style.transform = 'scaleX(0.8) scaleY(0.8)';
      }

      this.el.offsetWidth;

      requestAnimationFrame(() => {
        this._overlay.style.opacity = String(this.options.opacity);
        this.el.style.opacity = '1';

        if (this._isBottomSheet()) {
          this.el.style.bottom = '0';
        } else {
          this.el.style.top = this.options.endingTop;
          this.el.style.transform = 'scaleX(1) scaleY(1)';
        }
      });

      this._animationTimer = window.setTimeout(() => {
        if (typeof this.options.onOpenEnd === 'function') {
          this.options.onOpenEnd.call(this, this.el, this._openingTrigger);
        }
      }, duration);
    }

    _animateOut() {
      const duration = Number(this.options.outDuration) || 0;

      this._clearAnimationTimer();
      this._setTransition(duration);
      this._overlay.style.opacity = '0';
      this.el.style.opacity = '0';

      if (this._isBottomSheet()) {
        this.el.style.bottom = '-100%';
      } else {
        this.el.style.top = this.options.startingTop;
        this.el.style.transform = 'scaleX(0.8) scaleY(0.8)';
      }

      this._animationTimer = window.setTimeout(() => {
        this.el.style.display = 'none';
        this._overlay.remove();

        if (typeof this.options.onCloseEnd === 'function') {
          this.options.onCloseEnd.call(this, this.el);
        }

        if (this._openingTrigger) {
          this._openingTrigger.focus({ preventScroll: true });
        }
      }, duration);
    }

    _setTransition(duration) {
      const modalTransition = [
        `opacity ${duration}ms ease`,
        `top ${duration}ms cubic-bezier(0.23, 1, 0.32, 1)`,
        `bottom ${duration}ms cubic-bezier(0.23, 1, 0.32, 1)`,
        `transform ${duration}ms cubic-bezier(0.23, 1, 0.32, 1)`
      ].join(', ');

      this.el.style.transition = modalTransition;
      this._overlay.style.transition = `opacity ${duration}ms ease`;
    }

    _clearAnimationTimer() {
      if (this._animationTimer) {
        window.clearTimeout(this._animationTimer);
        this._animationTimer = null;
      }
    }

    _isBottomSheet() {
      return this.el.classList.contains('bottom-sheet');
    }
  }

  function handleTriggerClick(event) {
    const trigger = event.target.closest(triggerSelector);

    if (!trigger) {
      return;
    }

    const modalId = M.getIdFromTrigger(trigger);
    const modal = modalId ? document.getElementById(modalId) : null;
    const instance = modal ? Modal.getInstance(modal) : null;

    if (instance) {
      event.preventDefault();
      instance.open(trigger);
    }
  }

  M.Modal = Modal;
})(window, document);
