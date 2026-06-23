import Alpine from '@alpinejs/csp';

window.Alpine = Alpine;

Alpine.start();

const THEME_KEY = 'nutrition-theme';

function applyTheme(theme) {
	document.documentElement.classList.toggle('theme-dark', theme === 'dark');
	document.querySelectorAll('[data-theme-label]').forEach((el) => {
		el.textContent = theme === 'dark' ? 'Light' : 'Dark';
	});
}

function initializeThemeToggle() {
	const saved = localStorage.getItem(THEME_KEY);
	const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	const initial = saved || preferred;
	applyTheme(initial);

	const toggles = document.querySelectorAll('[data-theme-toggle]');
	toggles.forEach((toggle) => {
		toggle.addEventListener('click', () => {
			const nextTheme = document.documentElement.classList.contains('theme-dark') ? 'light' : 'dark';
			localStorage.setItem(THEME_KEY, nextTheme);
			applyTheme(nextTheme);
		});
	});
}

function renderSimpleCharts() {
	document.querySelectorAll('[data-simple-chart]').forEach((chartEl) => {
		const labels = JSON.parse(chartEl.getAttribute('data-labels') || '[]');
		const values = JSON.parse(chartEl.getAttribute('data-values') || '[]');
		const maxValue = Math.max(...values, 1);

		const grid = chartEl.querySelector('[data-chart-grid]');
		if (!grid) {
			return;
		}

		grid.innerHTML = '';

		labels.forEach((label, index) => {
			const rawValue = Number(values[index] ?? 0);
			const height = Math.max(8, Math.round((rawValue / maxValue) * 120));

			const col = document.createElement('div');
			col.className = 'simple-chart-col';

			const value = document.createElement('div');
			value.className = 'simple-chart-value';
			value.textContent = rawValue.toLocaleString();

			const bar = document.createElement('div');
			bar.className = 'simple-chart-bar';
			bar.style.height = `${height}px`;
			bar.setAttribute('title', `${label}: ${rawValue}`);

			const labelEl = document.createElement('div');
			labelEl.className = 'simple-chart-label';
			labelEl.textContent = label;

			col.append(value, bar, labelEl);
			grid.appendChild(col);
		});
	});
}

function initializeWordFlow() {
	const targets = document.querySelectorAll('[data-word-flow]');
	if (!targets.length) {
		return;
	}

	const prepareWords = (el) => {
		if (el.dataset.wordFlowReady === 'true') {
			return;
		}

		const text = el.textContent?.trim();
		if (!text) {
			return;
		}

		const tokens = text.match(/\S+\s*/g) || [text];
		el.textContent = '';

		tokens.forEach((token, index) => {
			const span = document.createElement('span');
			span.className = 'flow-word';
			span.style.setProperty('--word-index', String(index));
			span.textContent = token;
			el.appendChild(span);
		});

		el.dataset.wordFlowReady = 'true';
	};

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	targets.forEach((el) => prepareWords(el));

	if (prefersReducedMotion) {
		targets.forEach((el) => {
			el.classList.add('word-flow-reduced');
		});
	}
}

window.addEventListener('load', () => {
	initializeThemeToggle();
	renderSimpleCharts();
	initializeWordFlow();

	const loader = document.getElementById('page-loader');
	if (!loader) {
		return;
	}

	loader.classList.add('hidden');
});

document.addEventListener('click', (event) => {
	const clearLoginButton = event.target instanceof HTMLElement ? event.target.closest('[data-clear-login]') : null;
	if (clearLoginButton) {
		const emailInput = document.getElementById('email');
		const passwordInput = document.getElementById('password');
		const rememberInput = document.getElementById('remember_me');

		if (emailInput instanceof HTMLInputElement) {
			emailInput.value = '';
			emailInput.focus();
		}

		if (passwordInput instanceof HTMLInputElement) {
			passwordInput.value = '';
		}

		if (rememberInput instanceof HTMLInputElement) {
			rememberInput.checked = false;
		}

		return;
	}

	const target = event.target instanceof HTMLElement ? event.target.closest('a') : null;
	if (!target) {
		return;
	}

	const href = target.getAttribute('href');
	if (!href || href.startsWith('#') || target.getAttribute('target') === '_blank') {
		return;
	}

	const loader = document.getElementById('page-loader');
	if (loader) {
		loader.classList.remove('hidden');
	}
});
