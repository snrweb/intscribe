import React, { Component } from "react";

class UserFormEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      usernameError: "",
      profileImageError: "",
      username: "",
      profile_image: "",
      csrf_token: ""
    };

    this.onSubmit = this.onSubmit.bind(this);
    this.onChangeImage = this.onChangeImage.bind(this);
    this.onChange = this.onChange.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.goBackToLastUrl = this.goBackToLastUrl.bind();
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}user/editProfile`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          username: data.username,
          profile_image: data.profile_image,
          csrf_token: data.csrf_token
        });
      });
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert");
    if (image.name !== "") {
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
    }
    return true;
  }

  previewImage(image) {
    let preview = document.querySelector(".profile-image-preview");
    preview.src = URL.createObjectURL(image);
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  onChangeImage(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.setState({ profile_image: image });
      this.previewImage(image);
    }
  }

  onSubmit(e) {
    e.preventDefault();

    let formdata = new FormData();
    formdata.append("username", this.state.username);
    formdata.append("profile_image", this.state.profile_image);
    formdata.append("csrf_token", this.state.csrf_token);

    fetch(`${this.props.apiROOT}user/editProfile`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          this.setState({
            status: true,
            user_id: data.user_id,
            username: data.username
          });
          document.querySelector(
            ".navbar-username"
          ).innerHTML = data.username.split(" ")[0];
          window.history.back();
        }
      });
  }

  goBackToLastUrl() {
    window.history.back();
  }

  render() {
    return (
      <div className="user-edit-form">
        <button
          className="btn shadow user-edit-form-back-btn"
          onClick={this.goBackToLastUrl}
        >
          Back
        </button>
        <form
          onSubmit={this.onSubmit}
          className="shadow"
          encType="multipart/form-data"
        >
          <div className="title">
            <span>Edit Profile</span>
          </div>
          <small id="alert" />
          <div className="input-wrapper">
            <div
              style={{ textAlign: "center", marginBottom: 15 + "px" }}
              className="profile-image-preview-div"
            >
              {this.state.profile_image !== "" ? (
                <img
                  className="profile-image-preview"
                  src={`${this.props.root}public/images/profile_pic/${
                    this.state.profile_image
                  }`}
                  style={{ maxHeight: 90 + "px", maxWidth: 90 + "px" }}
                  alt={this.state.username}
                />
              ) : (
                <img
                  className="profile-image-preview"
                  src={`${this.props.root}public/images/profile_pic/avatar.jpg`}
                  style={{ maxHeight: 90 + "px", maxWidth: 90 + "px" }}
                  alt="avatar"
                />
              )}

              <div className="profile-image-input-div">
                <label htmlFor="profile-image-input" className="btn">
                  Change Image
                </label>
                <input
                  onChange={this.onChangeImage}
                  type="file"
                  name="profile_image"
                  className="profile-image-input"
                  id="profile-image-input"
                />
              </div>

              <div className="clear-float" />
            </div>
          </div>

          <div className="input-wrapper">
            <input
              onChange={this.onChange}
              className="username-input"
              type="text"
              placeholder="Firstname and Lastname"
              name="username"
              defaultValue={this.state.username}
              required
              maxLength="30"
            />
          </div>

          <div className="input-wrapper">
            <input
              type="submit"
              className="user-edit-form-submit btn"
              value="Update profile changes"
            />
          </div>
        </form>
      </div>
    );
  }
}

export default UserFormEditor;
