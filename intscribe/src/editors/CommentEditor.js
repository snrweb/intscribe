import React, { Component } from "react";
import { ReactComponent as BoldSVG } from "../images/svg/bold.svg";
import { ReactComponent as ItalicSVG } from "../images/svg/italic.svg";
import { ReactComponent as UListSVG } from "../images/svg/ulist.svg";
import { ReactComponent as OListSVG } from "../images/svg/olist.svg";
import { ReactComponent as LinkSVG } from "../images/svg/link.svg";
import { ReactComponent as UnlinkSVG } from "../images/svg/unlink.svg";
import { ReactComponent as PictureSVG } from "../images/svg/picture.svg";

class CommentEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      comment: "",
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
    this.image = this.image.bind(this);

    this.toggleEditorExecBtnColor = this.toggleEditorExecBtnColor.bind(this);
    this.cleanEditor = this.cleanEditor.bind(this);
    this.validateImage = this.validateImage.bind(this);
    this.uploadImage = this.uploadImage.bind(this);
  }

  cleanEditor(alertElem) {
    setTimeout(function() {
      let elem = document.querySelector(".comment-editor-wrapper");
      elem.classList.add("hide");
      elem.classList.remove("visible");

      alertElem.classList.remove("success-alert");
      alertElem.innerHTML = "";

      let editor = document.getElementById("comment_editor_frame");
      document.querySelector("#comment_editor_ta").value = "";
      editor.contentWindow.document.body.innerHTML = "";

      let parentExec = document.querySelector(
        ".editor-exec-btn-wrapper-comment"
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

  componentWillReceiveProps(nextProps) {
    if (this.props.setter !== nextProps.setter) {
      //bbb
    }
  }

  bold() {
    let elem = document.querySelector(".editor-c-bold-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.comment.document.execCommand("bold", false, null);
  }

  italic() {
    let elem = document.querySelector(".editor-c-italic-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.comment.document.execCommand("italic", false, null);
  }

  oList() {
    let elem = document.querySelector(".editor-c-olist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.comment.document.execCommand(
      "InsertOrderedList",
      false,
      "newOL"
    );
  }

  uList() {
    let elem = document.querySelector(".editor-c-ulist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.comment.document.execCommand(
      "InsertUnorderedList",
      false,
      "newUL"
    );
  }

  link() {
    let linkURL = prompt("Enter the URL for this link:", "http://");
    window.frames.comment.document.execCommand("CreateLink", false, linkURL);
  }

  unLink() {
    window.frames.comment.document.execCommand("Unlink", false, null);
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert2");
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
    let alertElem = document.querySelector("#alert2");

    this.alertMsg(alertElem, "success-alert", "Image is being uploaded...");

    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.uploadImage(image, alertElem);
    }
  }

  uploadImage(image, alertElem) {
    const formdata = new FormData();
    formdata.append("comment_image", image);
    fetch(`${this.props.apiROOT}imageUpload`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let src = `${this.props.root}public/images/comment_pic/${
            data.imageName
          }`;
          window.frames.comment.document.execCommand("insertimage", false, src);
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
    let alertElem = document.querySelector("#alert2");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(
      `${this.props.apiROOT}comment/add/${this.props.post_id}/${
        this.props.user_id
      }`,
      {
        method: "post",
        body: formdata
      }
    )
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.alertMsg(alertElem, "success-alert", "Comment added");

          this.cleanEditor(alertElem);
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      });
  }

  update(formdata) {
    let alertElem = document.querySelector("#alert2");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(`${this.props.apiROOT}comment/edit/${this.props.comment_id}`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.alertMsg(alertElem, "success-alert", "Comment updated");
          document.querySelector(
            `.pg-comment-in-${this.props.comment_id}`
          ).innerHTML = formdata.get("comment");

          this.cleanEditor(alertElem);
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      });
  }

  onSubmit(e) {
    e.preventDefault();

    let editor = document.getElementById("comment_editor_frame");
    document.querySelector("#comment_editor_ta").value =
      editor.contentWindow.document.body.innerHTML;
    const comment = document.querySelector("#comment_editor_ta").value;

    if (comment.trim().length < 5) {
      let alertElem = document.querySelector("#alert2");
      this.alertMsg(alertElem, "error-alert", "Comment is too short");
      return;
    }

    let formdata = new FormData();
    formdata.append("comment", comment);
    formdata.append("csrf_token", this.props.csrf_token);
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
        id="create-comment-form"
        onSubmit={this.onSubmit}
      >
        <small id="alert2" />

        <div className="reference">
          <p>{this.props.post_title}</p>
        </div>

        <div className="editor-exec-btn-wrapper editor-exec-btn-wrapper-comment">
          <button
            type="button"
            className="editor-c-bold-btn neutral"
            onClick={this.bold}
          >
            <BoldSVG />
          </button>
          <button
            type="button"
            className="editor-c-italic-btn neutral"
            onClick={this.italic}
          >
            <ItalicSVG />
          </button>
          <button
            type="button"
            className="editor-c-olist-btn neutral"
            onClick={this.oList}
          >
            <OListSVG />
          </button>
          <button
            type="button"
            className="editor-c-ulist-btn neutral"
            onClick={this.uList}
          >
            <UListSVG />
          </button>
          <button
            type="button"
            className="editor-c-link-btn neutral"
            onClick={this.link}
          >
            <LinkSVG />
          </button>
          <button
            type="button"
            className="editor-c-unlink-btn neutral"
            onClick={this.unLink}
          >
            <UnlinkSVG />
          </button>
          <label
            htmlFor="comment-image"
            className="editor-c-picture-btn neutral"
          >
            <PictureSVG />
          </label>
          <input
            type="file"
            id="comment-image"
            style={{ display: "none" }}
            onChange={this.image}
          />
        </div>

        <div className="input-wrapper">
          <textarea
            id="comment_editor_ta"
            name="comment"
            rows="10"
            onChange={this.onChange}
          />
          <iframe name="comment" id="comment_editor_frame" title="mmm" />
        </div>

        <div className="input-wrapper">
          <button type="submit" className="btn">
            {this.props.setter === "insert"
              ? "Submit Comment"
              : "Update Comment"}
          </button>
        </div>
      </form>
    );
  }
}

export default CommentEditor;
