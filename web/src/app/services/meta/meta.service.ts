import { Injectable } from '@angular/core';
import { Meta, Title } from '@angular/platform-browser';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root'
})
export class MetaService {

    static url = 'https://ozelden.com';
    static title = 'ozelden.com - özel ders almanın en akıllı yolu';
    static description = 'Özel ders almanın en akıllı yolu';
    static image = 'https://images.ozelden.com/resources/logo.png';

    constructor(private meta: Meta, private router: Router, private pageTitle: Title) {
        meta.addTag(MetaService.getOgUrlTag());
        meta.addTag(MetaService.getOgTitleTag());
        meta.addTag(MetaService.getOgDescriptionTag());
        meta.addTag(MetaService.getOgImageTag());
    }

    public updateOgMetaTags(title?: string) {
        this.pageTitle.setTitle(title || MetaService.title);
        this.meta.updateTag(MetaService.getDescriptionTag(title));
        this.meta.updateTag(MetaService.getOgUrlTag(`${MetaService.url}${this.router.url}`));
        this.meta.updateTag(MetaService.getOgTitleTag(title));
        this.meta.updateTag(MetaService.getOgDescriptionTag());
        this.meta.updateTag(MetaService.getOgImageTag());
    }

    private static getDescriptionTag(title?: string) {
        return {name: 'Description', content: title || MetaService.description}
    }

    private static getOgUrlTag(url?: string) {
        return {name: 'og:url', content: url || MetaService.url}
    }

    private static getOgTitleTag(title?: string) {
        return {name: 'og:title', content: title || MetaService.title}
    }

    private static getOgDescriptionTag() {
        return {name: 'og:description', content: MetaService.description}
    }

    private static getOgImageTag() {
        return {name: 'og:image', content: MetaService.image}
    }

}
