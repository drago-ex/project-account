// these JS + SCSS will be automatically available after installing the package
import { registerNajaExtensions } from './core/base.js';
import Spinner from './naja/spinner.js';
import HyperlinkDisable from './naja/hyperlink-disable.js';

// drago-ex extensions
import { SubmitButtonDisable } from 'drago-form';
import { ToastHandler } from 'drago-application';
import { BootstrapDropdowns } from 'drago-component';

// compostrap: the theme switch of the user menu, the button that shows a password and the meter of the strength
// of a new password
import { ThemeSwitcher } from 'compostrap/theme-switcher';
import { PasswordToggle } from 'compostrap/password-toggle';
import { Strength } from 'compostrap/strength';

// page styles
import './account.scss';

new ThemeSwitcher().initialize();
PasswordToggle.init();

// the translated names of the levels and the least length of a password are printed on the body by the layout
const { strengthMinLength, strengthWeak, strengthFair, strengthGood, strengthStrong } = document.body.dataset;
Strength.init({
	minLength: Number(strengthMinLength) || 8,
	messages: { weak: strengthWeak, fair: strengthFair, good: strengthGood, strong: strengthStrong },
});

registerNajaExtensions(
	Spinner,
	HyperlinkDisable,
	SubmitButtonDisable,
	ToastHandler,
	BootstrapDropdowns
);
