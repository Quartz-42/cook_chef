import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        addLabel: String,
        deleteLabel: String,
    };

    connect() {
        this.index = this.element.childElementCount;
        const btn = document.createElement("button");
        btn.setAttribute("class", "btn btn-sm btn-secondary mb-3");
        btn.innerText = this.addLabelValue || "Ajouter";
        btn.setAttribute("type", "button");
        btn.addEventListener("click", this.addElement);
        this.element.childNodes.forEach(this.addDeleteButton);
        this.element.append(btn);
    }

    /**
     *
     * @param {MouseEvent} e
     */
    addElement = (e) => {
        e.preventDefault();
        const element = document
            .createRange()
            .createContextualFragment(
                this.element.dataset.prototype.replaceAll(
                    /__name__/g,
                    this.index
                )
            ).firstElementChild;
        this.addDeleteButton(element);
        this.index++;
        e.currentTarget.insertAdjacentElement("beforebegin", element);
    };

    /**
     * @param {HTMLElement} item
     */
    addDeleteButton = (item) => {
        const btn = document.createElement("button");
        btn.setAttribute("class", "btn btn-sm btn-danger");
        btn.innerText = this.deleteLabelValue || "Supprimer";
        btn.setAttribute("type", "button");
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            item.remove();
        });
        item.appendChild(btn);
    };
}
