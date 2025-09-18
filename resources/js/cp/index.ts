import 'vanilla-colorful/hex-alpha-color-picker.js';
import 'vanilla-colorful/hex-input.js';

// Import all Stimulus controllers for control panel
import ColorPickerController from './controllers/color-picker-controller';
import FilterInputController from './controllers/filter-input-controller';
import FormController from './controllers/form-controller';
import IconPickerController from './controllers/icon-picker-controller';
import IncrementalSearchController from './controllers/incremental-search-controller';
import LineChartController from './controllers/line-chart-controller';
import PermissionGridController from './controllers/permission-grid-controller';
import SluggerController from './controllers/slugger-controller';
import SortableController from './controllers/sortable-controller';

// Register all controllers
window.Stimulus.register('color-picker', ColorPickerController);
window.Stimulus.register('filter-input', FilterInputController);
window.Stimulus.register('form', FormController);
window.Stimulus.register('icon-picker', IconPickerController);
window.Stimulus.register('incremental-search', IncrementalSearchController);
window.Stimulus.register('line-chart', LineChartController);
window.Stimulus.register('permission-grid', PermissionGridController);
window.Stimulus.register('slugger', SluggerController);
window.Stimulus.register('sortable', SortableController);
