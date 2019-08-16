import React, { Component } from "react";
import { ReactComponent as BoldSVG } from "../images/svg/bold.svg";
import { ReactComponent as ItalicSVG } from "../images/svg/italic.svg";
import { ReactComponent as UListSVG } from "../images/svg/ulist.svg";
import { ReactComponent as OListSVG } from "../images/svg/olist.svg";
import { ReactComponent as LinkSVG } from "../images/svg/link.svg";
import { ReactComponent as UnlinkSVG } from "../images/svg/unlink.svg";
import { ReactComponent as PictureSVG } from "../images/svg/picture.svg";

class SubCommentEditor extends Component {
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

    this.toggleEditorExecBtnColor = this.toggleEditorExecBtnColor.bind(this);
    this.cleanEditor = this.cleanEditor.bind(this);
    this.image = this.image.bind(this);

    this.validateImage = this.validateImage.bind(this);
    this.uploadImage = this.uploadImage.bind(this);
  }

  cleanEditor(alertElem) {
    setTimeout(function() {
      let elem = document.querySelector(".sub-comment-editor-wrapper");
      elem.classList.add("hide");
      elem.classList.remove("visible");

      alertElem.classList.remove("success-alert");
      alertElem.innerHTML = "";

      let editor = document.getElementById("sub_comment_editor_frame");
      document.querySelector("#sub_comment_editor_ta").value = "";
      editor.contentWindow.document.body.innerHTML = "";

      let parentExec = document.querySelector(
        ".editor-exec-btn-wrapper-sub-comment"
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
    if (
      this.props.setter !== nextProps.setter &&
      this.props.commentID !== nextProps.commentID
    ) {
      //bbb
    }
  }

  bold() {
    let elem = document.querySelector(".editor-sc-bold-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.sub_comment.document.execCommand("bold", false, null);
  }

  italic() {
    let elem = document.querySelector(".editor-sc-italic-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.sub_comment.document.execCommand("italic", false, null);
  }

  oList() {
    let elem = document.querySelector(".editor-sc-olist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.sub_comment.document.execCommand(
      "InsertOrderedList",
      false,
      "newOL"
    );
  }

  uList() {
    let elem = document.querySelector(".editor-sc-ulist-btn");
    this.toggleEditorExecBtnColor(elem);
    window.frames.sub_comment.document.execCommand(
      "InsertUnorderedList",
      false,
      "newUL"
    );
  }

  link() {
    let linkURL = prompt("Enter the URL for this link:", "http://");
    window.frames.sub_comment.document.execCommand(
      "CreateLink",
      false,
      linkURL
    );
  }

  unLink() {
    window.frames.sub_comment.document.execCommand("Unlink", false, null);
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert3");
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
    let alertElem = document.querySelector("#alert3");

    this.alertMsg(alertElem, "success-alert", "Image is being uploaded...");

    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.uploadImage(image, alertElem);
    }
  }

  uploadImage(image, alertElem) {
    const formdata = new FormData();
    formdata.append("scomment_image", image);
    fetch(`${this.props.apiROOT}imageUpload`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let src = `${this.props.root}public/images/scomment_pic/${
            data.imageName
          }`;
          window.frames.sub_comment.document.execCommand(
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
    let alertElem = document.querySelector("#alert3");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(
      `${this.props.apiROOT}subComment/add/${this.props.postID}/${
        this.props.commentID
      }/${this.props.userID}`,
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
    let alertElem = document.querySelector("#alert3");
    this.alertMsg(alertElem, "success-alert", "Please wait...");
    fetch(`${this.props.apiROOT}subComment/edit/${this.props.subCommentID}`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.alertMsg(alertElem, "success-alert", "Comment updated");
          document.querySelector(
            `.pg-sub-comment-in-${this.props.subCommentID}`
          ).innerHTML = formdata.get("sub_comment");

          this.cleanEditor(alertElem);
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      });
  }

  onSubmit(e) {
    e.preventDefault();

    let editor = document.getElementById("sub_comment_editor_frame");
    document.querySelector("#sub_comment_editor_ta").value =
      editor.contentWindow.document.body.innerHTML;
    const sub_comment = document.querySelector("#sub_comment_editor_ta").value;

    if (sub_comment.trim().length < 5) {
      let alertElem = document.querySelector("#alert3");
      this.alertMsg(alertElem, "error-alert", "Comment is too short");
      return;
    }

    let formdata = new FormData();
    formdata.append("sub_comment", sub_comment);
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
        id="create-sub-comment-form"
        onSubmit={this.onSubmit}
      >
        <small id="alert3" />

        <div
          className="editor-exec-btn-wrapper editor-exec-btn-wrapper-sub-comment"
          style={{ marginTop: 0 }}
        >
          <button
            type="button"
            className="editor-sc-bold-btn neutral"
            onClick={this.bold}
          >
            <BoldSVG />
          </button>
          <button
            type="button"
            className="editor-sc-italic-btn neutral"
            onClick={this.italic}
          >
            <ItalicSVG />
          </button>
          <button
            type="button"
            className="editor-sc-olist-btn neutral"
            onClick={this.oList}
          >
            <OListSVG />
          </button>
          <button
            type="button"
            className="editor-sc-ulist-btn neutral"
            onClick={this.uList}
          >
            <UListSVG />
          </button>
          <button
            type="button"
            className="editor-sc-link-btn neutral"
            onClick={this.link}
          >
            <LinkSVG />
          </button>
          <button
            type="button"
            className="editor-sc-unlink-btn neutral"
            onClick={this.unLink}
          >
            <UnlinkSVG />
          </button>
          <label
            htmlFor="sub-comment-image"
            className="editor-sc-picture-btn neutral"
          >
            <PictureSVG />
          </label>
          <input
            type="file"
            id="sub-comment-image"
            style={{ display: "none" }}
            onChange={this.image}
          />
        </div>

        <div className="input-wrapper">
          <textarea
            id="sub_comment_editor_ta"
            name="sub_comment"
            rows="10"
            onChange={this.onChange}
          />
          <iframe
            name="sub_comment"
            id="sub_comment_editor_frame"
            title="mmm"
          />
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

export default SubCommentEditor;
