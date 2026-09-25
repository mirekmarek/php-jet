const dialog = {
	open: ( id ) => {
		const d = dialog.getDialog( id );
		if(!d) {
			return;
		}
		d.showModal();
	},
	close: ( id ) => {
		const d = dialog.getDialog( id );
		if(!d) {
			return;
		}
		d.close();
	},
	getDialog: (id) => {
		const d = document.getElementById( id );
		if(!d) {
			alert('Unknown dialog '+id);
			return null;
		}
		if(!d['showModal']) {
			alert(id+' is not a dialog');
			return;
		}

		return d;
	}
};

const tabs = {
	select: ( tabs_id, target ) => {
		const tabs_container = document.getElementById(tabs_id+'_tabs_container');
		const container = document.getElementById(tabs_id+'_container');

		tabs_container.querySelectorAll('.tabs-tab').forEach( (node) => {
			node.classList.remove('active');
		} );

		container.querySelectorAll('.tab-pane').forEach( (node) => {
			node.classList.remove('active');
		} );

		document.getElementById(tabs_id+'_tab_'+target).classList.add('active');
		document.getElementById(tabs_id+'_'+target).classList.add('active');
	},

	showMobileMenu: ( anch ) => {
		const burger = anch.querySelector('.tabs-mobile-menu-icon');
		if(
			!burger ||
			!burger.checkVisibility()
		) {
			return;
		}


		const menu = anch.parentNode.parentNode;

		menu.classList.toggle('visible-menu');


	}
};

const effects = {
	getDuration: ( speed ) => {
		return 250;

		/*
		if(speed===undefined) {
			return 500;
		}

		if(speed==='fast') {
			return 250;
		}

		if(speed==='slow') {
			return 750;
		}

		return parseInt(speed);
		 */
	},

	fadeIn: ( id, speed, after ) => {
		const target = document.getElementById(id);
		const duration = effects.getDuration(speed);

		if (window.getComputedStyle(target).display !== 'none') {
			if(after) {
				after();
			}
			return;
		}

		target.style.transitionProperty = 'all';
		target.style.transitionDuration = duration + 'ms';
		target.style.opacity = '1';
		target.style.display = '';

		window.setTimeout( () => {
			target.style.removeProperty('transition-duration');
			target.style.removeProperty('transition-property');

			if(after) {
				after();
			}
		}, duration+20);
	},

	fadeOut: ( id,speed, after ) => {
		const target = document.getElementById(id);
		const duration = effects.getDuration(speed);

		if (window.getComputedStyle(target).display === 'none') {
			if(after) {
				after();
			}
			return;
		}

		target.style.transitionProperty = 'all';
		target.style.transitionDuration = duration + 'ms';
		target.style.opacity = '0';

		window.setTimeout( () => {
			target.style.display = 'none';
			target.style.removeProperty('transition-duration');
			target.style.removeProperty('transition-property');

			if(after) {
				after();
			}
		}, duration+20);

	},



	slideUp: ( id, speed, after ) => {
		const target = document.getElementById(id);
		const duration = effects.getDuration(speed);

		if (window.getComputedStyle(target).display === 'none') {
			if(after) {
				after();
			}
			return;
		}


		target.style.transitionProperty = 'height, margin, padding';
		target.style.transitionDuration = duration + 'ms';
		target.style.boxSizing = 'border-box';
		target.style.height = target.offsetHeight + 'px';
		target.offsetHeight;
		target.style.overflow = 'hidden';
		target.style.height = '0';
		target.style.paddingTop = '0';
		target.style.paddingBottom = '0';
		target.style.marginTop = '0';
		target.style.marginBottom = '0';

		window.setTimeout( () => {
			target.style.display = 'none';
			target.style.removeProperty('height');
			target.style.removeProperty('padding-top');
			target.style.removeProperty('padding-bottom');
			target.style.removeProperty('margin-top');
			target.style.removeProperty('margin-bottom');
			target.style.removeProperty('overflow');
			target.style.removeProperty('transition-duration');
			target.style.removeProperty('transition-property');

			if(after) {
				after();
			}
		}, duration+20);

	},

	slideDown: ( id,speed, after ) => {
		const target = document.getElementById(id);
		const duration = effects.getDuration(speed);

		if (window.getComputedStyle(target).display !== 'none') {
			if(after) {
				after();
			}
			return;
		}


		target.style.removeProperty('display');
		let display = window.getComputedStyle(target).display;
		if (display === 'none') {
			display = 'block';
		}
		target.style.display = display;
		let height = target.offsetHeight;
		target.style.overflow = 'hidden';
		target.style.height = '0';
		target.style.paddingTop = '0';
		target.style.paddingBottom = '0';
		target.style.marginTop = '0';
		target.style.marginBottom = '0';
		target.offsetHeight;
		target.style.boxSizing = 'border-box';
		target.style.transitionProperty = "height, margin, padding";
		target.style.transitionDuration = duration + 'ms';
		target.style.height = height + 'px';
		target.style.removeProperty('padding-top');
		target.style.removeProperty('padding-bottom');
		target.style.removeProperty('margin-top');
		target.style.removeProperty('margin-bottom');
		window.setTimeout( () => {
			target.style.removeProperty('height');
			target.style.removeProperty('overflow');
			target.style.removeProperty('transition-duration');
			target.style.removeProperty('transition-property');

			if(after) {
				after();
			}
		}, duration+20);

	},

	slideToggle: ( id, speed, after ) => {
		if (window.getComputedStyle(document.getElementById(id)).display === 'none') {
			effects.slideDown( id, speed, after);
		} else {
			effects.slideUp( id, speed, after);
		}

	},

	scrollTo: ( id ) => {

		const element = document.getElementById( id );

		element.scrollIntoView({ behavior: "smooth", block: "start", inline: "nearest" });

	}
};

const visibility = {
	show: ( id ) => {
		const el = document.getElementById( id );

		if (window.getComputedStyle( el ).display === 'none') {
			el.style.display = '';
		}
	},

	hide: ( id ) => {
		const el = document.getElementById( id );

		if (window.getComputedStyle( el ).display !== 'none') {
			el.style.display = 'none';
		}
	},

	toggle: (id) => {
		const el = document.getElementById( id );

		if (window.getComputedStyle( el ).display === 'none') {
			el.style.display = '';
		} else {
			el.style.display = 'none';
		}
	}
};
