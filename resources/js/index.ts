import '@github/relative-time-element';
import { Application } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';
import ky from 'ky';

import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/hotkeys';
import './bootstrap/turbo';
import { getCookie } from './utils';

declare global {
    const Waterhole: Waterhole;

    interface Window {
        Stimulus: Application;
        Waterhole: Waterhole;
    }
}

export interface Waterhole {
    userId: number;
    debug: boolean;
    alerts: AlertsElement;
    fetch: typeof ky;
    fetchError: (response?: Response) => void;
    documentTitle: DocumentTitle;
    echoConfig: any;
}

Object.defineProperty(Waterhole, 'alerts', {
    get: () => document.getElementById('alerts'),
});

window.Stimulus = Application.start();

// Import all Stimulus controllers
import ActionMenuController from './controllers/action-menu-controller';
import AlertController from './controllers/alert-controller';
import CommentController from './controllers/comment-controller';
import CommentRepliesController from './controllers/comment-replies-controller';
import ComposerController from './controllers/composer-controller';
import CopyLinkController from './controllers/copy-link-controller';
import LoadBackwardsController from './controllers/load-backwards-controller';
import LoginController from './controllers/login-controller';
import MentionsController from './controllers/mentions-controller';
import ModalController from './controllers/modal-controller';
import NotificationsPopupController from './controllers/notifications-popup-controller';
import PageController from './controllers/page-controller';
import PostController from './controllers/post-controller';
import PostFeedController from './controllers/post-feed-controller';
import PostPageController from './controllers/post-page-controller';
import QuotableController from './controllers/quotable-controller';
import RevealController from './controllers/reveal-controller';
import ScrollspyController from './controllers/scrollspy-controller';
import SimilarPostsController from './controllers/similar-posts-controller';
import TextEditorController from './controllers/text-editor-controller';
import ThemeController from './controllers/theme-controller';
import TruncatedController from './controllers/truncated-controller';
import TurboFrameController from './controllers/turbo-frame-controller';
import UploadsController from './controllers/uploads-controller';
import WatchScrollController from './controllers/watch-scroll-controller';
import WatchStickyController from './controllers/watch-sticky-controller';

// Register all controllers
window.Stimulus.register('action-menu', ActionMenuController);
window.Stimulus.register('alert', AlertController);
window.Stimulus.register('comment', CommentController);
window.Stimulus.register('comment-replies', CommentRepliesController);
window.Stimulus.register('composer', ComposerController);
window.Stimulus.register('copy-link', CopyLinkController);
window.Stimulus.register('load-backwards', LoadBackwardsController);
window.Stimulus.register('login', LoginController);
window.Stimulus.register('mentions', MentionsController);
window.Stimulus.register('modal', ModalController);
window.Stimulus.register('notifications-popup', NotificationsPopupController);
window.Stimulus.register('page', PageController);
window.Stimulus.register('post', PostController);
window.Stimulus.register('post-feed', PostFeedController);
window.Stimulus.register('post-page', PostPageController);
window.Stimulus.register('quotable', QuotableController);
window.Stimulus.register('reveal', RevealController);
window.Stimulus.register('scrollspy', ScrollspyController);
window.Stimulus.register('similar-posts', SimilarPostsController);
window.Stimulus.register('text-editor', TextEditorController);
window.Stimulus.register('theme', ThemeController);
window.Stimulus.register('truncated', TruncatedController);
window.Stimulus.register('turbo-frame', TurboFrameController);
window.Stimulus.register('uploads', UploadsController);
window.Stimulus.register('watch-scroll', WatchScrollController);
window.Stimulus.register('watch-sticky', WatchStickyController);

Waterhole.fetch = ky.create({
    headers: { 'X-XSRF-TOKEN': getCookie('XSRF-TOKEN') || undefined },
    hooks: {
        beforeError: [
            (error) => {
                Waterhole.fetchError(error.response);
                return error;
            },
        ],
    },
});
