import globalData from '@csstools/postcss-global-data';
import postcssEach from 'postcss-each';
import postcssMixins from 'postcss-mixins';
import postcssPresetEnv from 'postcss-preset-env';

export default {
    parser: 'postcss-scss', // for single-line comment support
    plugins: [
        globalData({
            files: ['./resources/css/system/breakpoints.css'],
        }),
        postcssEach,
        postcssMixins({
            mixinsFiles: ['./resources/css/system/mixins.css'],
        }),
        postcssPresetEnv({
            stage: 2,
            features: {
                'nesting-rules': true, // Stage 1
            },
        }),
    ],
};
