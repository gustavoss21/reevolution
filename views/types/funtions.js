class BuildHTML {
  constructor(element_class) {
    this.element_super = document.getElementsByClassName(element_class)[0];
    this.elementsChildren = [];
  }

  /**
   * cria HTML para timeline
   */
  timeline(Object_data) {
    //create container
    //create elements
    Object_data.forEach((datas) => {
      let stage_label = this.createElement("span", "title-item", "Estagio:");
      let stage = this.createElement(
        "span",
        "title-item-stage",
        datas.topic.stage.name
      );
      let content_stage = this.createElement("div", "", stage_label + stage);

      let stage_priority_label = this.createElement(
        "span",
        "title-item",
        "Estagio:"
      );
      let stage_priority = this.createElement(
        "span",
        "title-item-stage-priority",
        datas.topic.stage.priority_op
      );
      let content_priority = this.createElement(
        "div",
        "",
        stage_priority_label + stage_priority
      );

      let stage_domain_label = this.createElement(
        "span",
        "title-item",
        "Estagio:"
      );
      let stage_domain = this.createElement(
        "span",
        "title-item-stage-domain",
        datas.topic.stage.domain_level
      );
      let content_domain = this.createElement(
        "div",
        "",
        stage_domain_label + stage_domain
      );

      let stage_status_label = this.createElement(
        "span",
        "title-item",
        "Estagio:"
      );
      let stage_status = this.createElement(
        "span",
        "title-item-stage-status",
        datas.topic.stage.status
      );
      let content_status = this.createElement(
        "div",
        "",
        stage_status_label + stage_status
      );

      let stage_score_label = this.createElement(
        "span",
        "title-item",
        "Estagio:"
      );
      let stage_score = this.createElement(
        "span",
        "title-item-stage-score",
        datas.topic.stage.score
      );
      let content_score = this.createElement(
        "div",
        "",
        stage_score_label + stage_score
      );

      let stage_updated_at_label = this.createElement(
        "span",
        "title-item",
        "Estagio:"
      );
      let stage_updated_at = this.createElement(
        "span",
        "title-item-stage-updated_at",
        datas.topic.stage.updated_at
      );
      let content_updated = this.createElement(
        "div",
        "",
        stage_updated_at_label + stage_updated_at
      );

      let title = this.createElement("h2", "theme-title", datas.name);
      let title_topic = this.createElement(
        "h3",
        "theme-topic",
        datas.topic.name
      );
      let content = this.createElement(
        "div",
        "theme-item",
        title +
          title_topic +
          content_stage +
          content_priority +
          content_domain +
          content_status +
          content_score +
          content_updated,
        { id: datas.slug }
      );

      let link = this.createElement("a", "", content, {
        href: `theme/${datas.id}`,
      });

      return link;
    });
  }

  createElement(tag, class_name, chaldrin, attributes = {}) {
    let element = document.createElement(tag);
    element.className = class_name;
    element.innerHtml = chaldrin;

    Object.keys(attributes).forEach((key) => {
      element.setAttribute(key, attributes[key]);
    });

    return element;
  }
}


(new BuildHTML).tese()