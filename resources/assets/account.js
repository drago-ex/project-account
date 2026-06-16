// these JS + SCSS will be automatically available after installing the package
import { registerNajaExtensions } from "./core/base.js";
import { initAppDropdowns } from "drago-component/dropdown";
import ThemeSwitcher from "theme-switcher-compostrap";
import "bootstrap/js/dist/tab";
import Spinner from "./naja/spinner.js";
import HyperlinkDisable from "./naja/hyperlink-disable.js";
import { PasswordToggle, SubmitButtonDisable } from "drago-form";
import { ToastHandler } from "drago-application";
import "./account.scss";

document.addEventListener("DOMContentLoaded", () => {
	initAppDropdowns();
});

new ThemeSwitcher().initialize();
registerNajaExtensions(
	Spinner,
	HyperlinkDisable,
	PasswordToggle,
	SubmitButtonDisable,
	ToastHandler
);
