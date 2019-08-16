import React, { Component } from "react";
import { ReactComponent as BoldSVG } from "../images/svg/bold.svg";
import { ReactComponent as ItalicSVG } from "../images/svg/italic.svg";
import { ReactComponent as UListSVG } from "../images/svg/ulist.svg";
import { ReactComponent as OListSVG } from "../images/svg/olist.svg";
import { ReactComponent as LinkSVG } from "../images/svg/link.svg";
import { ReactComponent as UnlinkSVG } from "../images/svg/unlink.svg";
import { ReactComponent as PictureSVG } from "../images/svg/picture.svg";

class ArticleEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      userInterests: [],
      post_title: "",
      post_int: "Pick an interest",
      main_post: "",
      csrf_token: ""
    };

    this.onSubmit = this.onSubmit.bind(this);
    this.onChange = this.onChange.bind(this);
    this.alertMsg = this.alertMsg.bind(this);

    this.insert = this.insert.bind(this);
    this.update = this.update.bind(this);

    this.bold = this.bold.bind(this);
    this.italic = this.italic.bind(this);
    this.oList = this.oList.bind(this);
    this.uList = this.uList.bind(this);
    this.link = this.link.bind(this);
    this.unLink = this.unLink.bind(this);

    this.toggleEditorExecBtnColor = this.toggleEditorExecBtnColor.bind(this);
    this.cleanEditor = this.cleanEditor.bind(this);
    this.image = this.image.bind(this);

    this.validateImage = this.validateImage.bind(this);
    this.uploadImage = this.uploadImage.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}post/insertArticle`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          userInterests: data.userInterests,
          csrf_token: data.csrf_token
        });
      });
  }

  cleanEditor(alertElem) {
    setTimeout(function() {
      let elem = document.querySelector(".article-editor-wrapper");
      elem.classList.add("hide");
      elem.classList.remove("visible");

      alertElem.classList.remove("success-alert");
      alertElem.innerHTML = "";

      let editor = document.getElementById("article_editor_frame");
      document.querySelector("#article_editor_ta").value = "";
      editor.contentWindow.document.body.innerHTML = "";

      let parentExec = document.querySelector(
        ".editor-exec-btn-wrapper-article"
      );
      let execBtns = parentExec.querySelectorAll(".green-fill").length;
      for (let i = 0; i < execBtns; i++) {
        parentExec.querySelectorAll(".green-fill")[i].classList.add("neutral");

        parentExec
          .querySelectorAll(".green-fill") // eslint-disable-next-line
          [i].classList.remove("green-fill");
      }
    }, 1000);
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.remove("success-alert");
    elem.classList.remove("error-alert");

    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  toggleEditorExecBtnColor(elem) {
    if (elem.classList.contains("neutral")) {
      elem.classList.remove("neutral");
      elem.classList.add("green-fill");
    } else {
      elem.classList.add("neutral");
      elem.classList.remove("green-fill");
    }
  }

  bold() {
    let elem = document.querySelector(".editor-bold-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.main_post.document.execCommand("bold", false, null);
  }

  italic() {
    let elem = document.querySelector(".editor-italic-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.main_post.document.execCommand("italic", false, null);
  }

  oList() {
    let elem = document.querySelector(".editor-olist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.main_post.document.execCommand(
      "InsertOrderedList",
      false,
      "newOL"
    );
  }

  uList() {
    let elem = document.querySelector(".editor-ulist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.main_post.document.execCommand(
      "InsertUnorderedList",
      false,
      "newUL"
    );
  }

  link() {
    let linkURL = prompt("Enter the URL for this link:", "http://");
    window.frames.main_post.document.execCommand("CreateLink", false, linkURL);
  }

  unLink() {
    window.frames.main_post.document.execCommand("Unlink", false, null);
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert");
    if (image.name === "") {
      this.alertMsg(alertElem, "error-alert", "Please select an image");
      this.setState({ hasError: true });
      return false;
    }

    let extension = image.type
      .split("/")
      .pop()
      .toLowerCase();
    if (["jpg", "png", "jpeg"].indexOf(extension) === -1) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "The selected file is not an image"
      );
      return false;
    }

    if (image.size > 4500000) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Image should not be more than 4mb"
      );
      return false;
    }
    return true;
  }

  image(e) {
    let alertElem = document.querySelector("#alert");

    this.alertMsg(alertElem, "success-alert", "Image is being uploaded...");

    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.uploadImage(image, alertElem);
    }
  }

  uploadImage(image, alertElem) {
    const formdata = new FormData();
    formdata.append("post_image", image);
    fetch(`${this.props.apiROOT}imageUpload`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let src = `${this.props.root}public/images/post_pic/${
            data.imageName
          }`;
          window.frames.main_post.document.execCommand(
            "insertimage",
            false,
            src
          );
          this.alertMsg(alertElem, "success-alert", "Image uploaded");
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  insert(formdata) {
    let alertElem = document.querySelector("#alert");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(`${this.props.apiROOT}post/insertArticle`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.alertMsg(alertElem, "success-alert", "Article posted");

          let countElem = document.querySelector(".c1-user-post-count span");
          if (countElem) {
            countElem.innerHTML = parseInt(countElem.innerHTML) + 1;
          }
          this.cleanEditor(alertElem);
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      });
  }

  update(formdata) {
    let alertElem = document.querySelector("#alert");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(`${this.props.apiROOT}post/editArticle/${this.props.post_id}`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.alertMsg(alertElem, "success-alert", "Article updated");
          document.querySelector(".pg-post-in").innerHTML = formdata.get(
            "main_post"
          );
          document.querySelector(".pg-interest-name").innerHTML = formdata.get(
            "post_int"
          );
          document.querySelector(".pg-post-title").innerHTML = formdata.get(
            "post_title"
          );

          this.cleanEditor(alertElem);
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      });
  }

  onSubmit(e) {
    e.preventDefault();

    let editor = document.getElementById("article_editor_frame");
    document.querySelector("#article_editor_ta").value =
      editor.contentWindow.document.body.innerHTML;
    const main_post = document.querySelector("#article_editor_ta").value;

    const title = document.querySelector(".editor-post-title").value;
    const interest = document.querySelector(".editor-post-interest-lists")
      .value;

    if (this.state.post_title.trim().length < 10 && title.trim().length < 10) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(alertElem, "error-alert", "Article title is too short");
      return;
    }

    let alertElem = document.querySelector("#alert");
    if (
      this.state.post_int.trim() === "Pick an interest" &&
      interest.trim() === "Pick an interest"
    ) {
      this.alertMsg(alertElem, "error-alert", "Select interest");
      return;
    }

    if (main_post.trim().length < 20) {
      this.alertMsg(alertElem, "error-alert", "Article is too short");
      return;
    }

    let formdata = new FormData();
    formdata.append("post_title", title);
    formdata.append("post_int", interest);
    formdata.append("main_post", main_post);
    formdata.append("csrf_token", this.state.csrf_token);
    formdata.append("isJSeditor", 1);

    if (this.props.setter === "insert") {
      this.insert(formdata);
    } else if (this.props.setter === "update") {
      this.update(formdata);
    }
  }

  render() {
    return (
      <form
        className="editor shadow"
        id="create-article-form"
        onSubmit={this.onSubmit}
      >
        <small id="alert" />
        <div className="input-wrapper">
          <input
            className="title editor-post-title"
            type="text"
            name="post_title"
            maxLength="200"
            placeholder="Title..."
            onChange={this.onChange}
            defaultValue={this.props.post_title}
          />
        </div>

        <div className="input-wrapper">
          <select
            name="post_int"
            className="select editor-post-interest-lists"
            onChange={this.onChange}
          >
            <option>Pick an interest</option>
            {this.state.userInterests.map(u => (
              <option
                key={u.interest}
                selected={this.props.post_int === u.interest && true}
              >
                {" "}
                {u.interest}{" "}
              </option>
            ))}
          </select>
        </div>

        <div className="editor-exec-btn-wrapper editor-exec-btn-wrapper-article">
          <button
            type="button"
            className="editor-bold-btn neutral"
            onClick={this.bold}
          >
            <BoldSVG />
          </button>
          <button
            type="button"
            className="editor-italic-btn neutral"
            onClick={this.italic}
          >
            <ItalicSVG />
          </button>
          <button
            type="button"
            className="editor-olist-btn neutral"
            onClick={this.oList}
          >
            <OListSVG />
          </button>
          <button
            type="button"
            className="editor-ulist-btn neutral"
            onClick={this.uList}
          >
            <UListSVG />
          </button>
          <button
            type="button"
            className="editor-link-btn neutral"
            onClick={this.link}
          >
            <LinkSVG />
          </button>
          <button
            type="button"
            className="editor-unlink-btn neutral"
            onClick={this.unLink}
          >
            <UnlinkSVG />
          </button>

          <label htmlFor="post-image" className="editor-picture-btn neutral">
            <PictureSVG />
          </label>
          <input
            type="file"
            id="post-image"
            style={{ display: "none" }}
            onChange={this.image}
          />
        </div>

        <div className="input-wrapper">
          <textarea
            id="article_editor_ta"
            name="main_post"
            rows="10"
            placeholder="Article..."
            onChange={this.onChange}
          />
          <iframe name="main_post" id="article_editor_frame" title="mmm" />
        </div>

        <div className="input-wrapper">
          <button type="submit" className="btn">
            {this.props.setter === "insert"
              ? "Submit Article"
              : "Update Article"}
          </button>
        </div>
      </form>
    );
  }
}

export default ArticleEditor;
