export function initAppDropdowns(selector = "[data-app-dropdown-toggle]") {
	const toggles = document.querySelectorAll(selector);
	const closeDropdown = (toggle) => {
		const dropdown = toggle.closest(".dropdown");
		const menu = dropdown?.querySelector(".dropdown-menu");

		if (!dropdown || !menu) {
			return;
		}

		toggle.setAttribute("aria-expanded", "false");
		toggle.classList.remove("show");
		menu.classList.remove("show");
	};
	const closeOtherDropdowns = (currentToggle) => {
		toggles.forEach((toggle) => {
			if (toggle !== currentToggle) {
				closeDropdown(toggle);
			}
		});
	};

	toggles.forEach((toggle) => {
		const dropdown = toggle.closest(".dropdown");
		const menu = dropdown?.querySelector(".dropdown-menu");

		if (!dropdown || !menu) {
			return;
		}

		const setOpen = (open) => {
			if (open) {
				closeOtherDropdowns(toggle);
			}

			toggle.setAttribute("aria-expanded", String(open));
			toggle.classList.toggle("show", open);
			menu.classList.toggle("show", open);
		};

		toggle.addEventListener("click", (event) => {
			event.preventDefault();
			event.stopPropagation();
			setOpen(toggle.getAttribute("aria-expanded") !== "true");
		});

		document.addEventListener("click", (event) => {
			if (!dropdown.contains(event.target)) {
				closeDropdown(toggle);
			}
		});

		document.addEventListener("keydown", (event) => {
			if (event.key === "Escape") {
				closeDropdown(toggle);
			}
		});
	});
}
