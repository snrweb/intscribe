import React, { Component } from "react";
import TimeDiff from "../helpers/TimeDiff";
import ColumnOne from "../components/ColumOne";
import ColumnThree from "../components/ColumnThree";

class Notification extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoggedInUserID: 1,
      unread: [],
      read: []
    };

    this.notifications = this.notifications.bind(this);
    this.unReadNotifications = this.unReadNotifications.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}notification`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          read: data.read,
          unread: data.unread
        });
      });
  }

  notifications() {
    let i = 0;
    const rows = [];
    while (i < this.state.read.length) {
      if (this.state.read[i].type === "Follow") {
        let fcount = 0;
        let f = i;
        while (true) {
          if (
            f < this.state.read.length &&
            this.state.read[f].type === "Follow"
          ) {
            fcount++;
            f++;
            i++;
          } else {
            if (fcount > 1) {
              if (fcount - 1 > 1) {
                rows.push(
                  <li key={this.state.read[f - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        f - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[f - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[f - 1].username} </span>
                    </a>{" "}
                    and {f - 1} others followed you
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[f - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.read[f - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        f - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[f - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[f - 1].username} </span>
                    </a>{" "}
                    and <span>{this.state.read[f].username} </span> followed you
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[f - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.read[f - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.read[
                      f - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.read[f - 1].user_id
                    }`}
                  >
                    <span>{this.state.read[f - 1].username} </span>
                  </a>{" "}
                  followed you
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.read[f - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.read[i].type === "Bookmark") {
        let bcount = 0;
        let b = i;
        while (true) {
          if (
            b < this.state.read.length &&
            this.state.read[b].type === "Bookmark"
          ) {
            bcount++;
            b++;
            i++;
          } else {
            if (bcount > 1) {
              if (bcount - 1 > 1) {
                rows.push(
                  <li key={this.state.read[b - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        b - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[b - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[b - 1].username} </span>
                    </a>{" "}
                    and {b - 1} others bookmarked your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        b - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[b - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[b - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[b - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.read[b - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        b - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[b - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[b - 1].username} </span>
                    </a>{" "}
                    and one other person bookmarked your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        b - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[b - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[b - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[b - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.read[b - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.read[
                      b - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.read[b - 1].user_id
                    }`}
                  >
                    <span>{this.state.read[b - 1].username} </span>
                  </a>{" "}
                  bookmarked your post{" "}
                  <a
                    href={`${this.props.root}post/${this.state.read[
                      b - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.read[b - 1].post_id
                    }`}
                  >
                    <span>{this.state.read[b - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.read[b - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.read[i].type === "Comment") {
        let kcount = 0;
        let k = i;
        while (true) {
          if (
            k < this.state.read.length &&
            this.state.read[k].type === "Comment"
          ) {
            kcount++;
            k++;
            i++;
          } else {
            if (kcount > 1) {
              if (kcount - 1 > 1) {
                rows.push(
                  <li key={this.state.read[k - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        k - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[k - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[k - 1].username} </span>
                    </a>{" "}
                    and {k - 1} others commented on your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        k - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[k - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[k - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[k - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.read[k - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        k - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[k - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[k - 1].username} </span>
                    </a>{" "}
                    and one other person commented on your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        k - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[k - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[k - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff date={this.state.read[k - 1].created_at} />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.read[k - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.read[
                      k - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.read[k - 1].user_id
                    }`}
                  >
                    <span>{this.state.read[k - 1].username} </span>
                  </a>{" "}
                  commented on your post{" "}
                  <a
                    href={`${this.props.root}post/${this.state.read[
                      k - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.read[k - 1].post_id
                    }`}
                  >
                    <span>{this.state.read[k - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.read[k - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.read[i].type === "SubComment") {
        let skcount = 0;
        let sk = i;
        while (true) {
          if (
            sk < this.state.read.length &&
            this.state.read[sk].type === "SubComment"
          ) {
            skcount++;
            sk++;
            i++;
          } else {
            if (skcount > 1) {
              if (skcount - 1 > 1) {
                rows.push(
                  <li key={this.state.read[sk - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        sk - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[sk - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[sk - 1].username} </span>
                    </a>{" "}
                    and {sk - 1} others commented on your comment on{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        sk - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[sk - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[sk - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.read[sk - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.read[sk - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.read[
                        sk - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.read[sk - 1].user_id
                      }`}
                    >
                      <span>{this.state.read[sk - 1].username} </span>
                    </a>{" "}
                    and one other person commented on your comment on{" "}
                    <a
                      href={`${this.props.root}post/${this.state.read[
                        sk - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.read[sk - 1].post_id
                      }`}
                    >
                      <span>{this.state.read[sk - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.read[sk - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.read[sk - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.read[
                      sk - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.read[sk - 1].user_id
                    }`}
                  >
                    <span>{this.state.read[sk - 1].username} </span>
                  </a>{" "}
                  commented on your comment on{" "}
                  <a
                    href={`${this.props.root}post/${this.state.read[
                      sk - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.read[sk - 1].post_id
                    }`}
                  >
                    <span>{this.state.read[sk - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.read[sk - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      }
    }
    return rows;
  }

  unReadNotifications() {
    let i = 0;
    const rows = [];
    while (i < this.state.unread.length) {
      if (this.state.unread[i].type === "Follow") {
        let fcount = 0;
        let f = i;
        while (true) {
          if (
            f < this.state.unread.length &&
            this.state.unread[f].type === "Follow"
          ) {
            fcount++;
            f++;
            i++;
          } else {
            if (fcount > 1) {
              if (fcount - 1 > 1) {
                rows.push(
                  <li key={this.state.unread[f - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        f - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[f - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[f - 1].username} </span>
                    </a>{" "}
                    and {f - 1} others followed you
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[f - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.unread[f - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        f - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[f - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[f - 1].username} </span>
                    </a>{" "}
                    and <span>{this.state.unread[f].username} </span> followed
                    you
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[f - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.unread[f - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.unread[
                      f - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.unread[f - 1].user_id
                    }`}
                  >
                    <span>{this.state.unread[f - 1].username} </span>
                  </a>{" "}
                  followed you
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.unread[f - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.unread[i].type === "Bookmark") {
        let bcount = 0;
        let b = i;
        while (true) {
          if (
            b < this.state.unread.length &&
            this.state.unread[b].type === "Bookmark"
          ) {
            bcount++;
            b++;
            i++;
          } else {
            if (bcount > 1) {
              if (bcount - 1 > 1) {
                rows.push(
                  <li key={this.state.unread[b - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        b - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[b - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[b - 1].username} </span>
                    </a>{" "}
                    and {b - 1} others bookmarked your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        b - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[b - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[b - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[b - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.unread[b - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        b - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[b - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[b - 1].username} </span>
                    </a>{" "}
                    and one other person bookmarked your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        b - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[b - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[b - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[b - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.unread[b - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.unread[
                      b - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.unread[b - 1].user_id
                    }`}
                  >
                    <span>{this.state.unread[b - 1].username} </span>
                  </a>{" "}
                  bookmarked your post{" "}
                  <a
                    href={`${this.props.root}post/${this.state.unread[
                      b - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.unread[b - 1].post_id
                    }`}
                  >
                    <span>{this.state.unread[b - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.unread[b - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.unread[i].type === "Comment") {
        let kcount = 0;
        let k = i;
        while (true) {
          if (
            k < this.state.unread.length &&
            this.state.unread[k].type === "Comment"
          ) {
            kcount++;
            k++;
            i++;
          } else {
            if (kcount > 1) {
              if (kcount - 1 > 1) {
                rows.push(
                  <li key={this.state.unread[k - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        k - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[k - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[k - 1].username} </span>
                    </a>{" "}
                    and {k - 1} others commented on your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        k - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[k - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[k - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[k - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.unread[k - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        k - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[k - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[k - 1].username} </span>
                    </a>{" "}
                    and one other person commented on your post{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        k - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[k - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[k - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[k - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.unread[k - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.unread[
                      k - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.unread[k - 1].user_id
                    }`}
                  >
                    <span>{this.state.unread[k - 1].username} </span>
                  </a>{" "}
                  commented on your post{" "}
                  <a
                    href={`${this.props.root}post/${this.state.unread[
                      k - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.unread[k - 1].post_id
                    }`}
                  >
                    <span>{this.state.unread[k - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff date={this.state.unread[k - 1].created_at} />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      } else if (this.state.unread[i].type === "SubComment") {
        let skcount = 0;
        let sk = i;
        while (true) {
          if (
            sk < this.state.unread.length &&
            this.state.unread[sk].type === "SubComment"
          ) {
            skcount++;
            sk++;
            i++;
          } else {
            if (skcount > 1) {
              if (skcount - 1 > 1) {
                rows.push(
                  <li key={this.state.unread[sk - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        sk - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[sk - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[sk - 1].username} </span>
                    </a>{" "}
                    and {sk - 1} others commented on your comment on{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        sk - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[sk - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[sk - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[sk - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              } else {
                rows.push(
                  <li key={this.state.unread[sk - 1].notification_id}>
                    <a
                      href={`${this.props.root}user/${this.state.unread[
                        sk - 1
                      ].username.replace(/ /g, "-")}-${
                        this.state.unread[sk - 1].user_id
                      }`}
                    >
                      <span>{this.state.unread[sk - 1].username} </span>
                    </a>{" "}
                    and one other person commented on your comment on{" "}
                    <a
                      href={`${this.props.root}post/${this.state.unread[
                        sk - 1
                      ].post_title.replace(/ /g, "-")}-${
                        this.state.unread[sk - 1].post_id
                      }`}
                    >
                      <span>{this.state.unread[sk - 1].post_title}</span>
                    </a>
                    <small className="notification-time">
                      {" "}
                      <TimeDiff
                        date={this.state.unread[sk - 1].created_at}
                      />{" "}
                    </small>
                  </li>
                );
              }
            } else {
              rows.push(
                <li key={this.state.unread[sk - 1].notification_id}>
                  <a
                    href={`${this.props.root}user/${this.state.unread[
                      sk - 1
                    ].username.replace(/ /g, "-")}-${
                      this.state.unread[sk - 1].user_id
                    }`}
                  >
                    <span>{this.state.unread[sk - 1].username} </span>
                  </a>{" "}
                  commented on your comment on{" "}
                  <a
                    href={`${this.props.root}post/${this.state.unread[
                      sk - 1
                    ].post_title.replace(/ /g, "-")}-${
                      this.state.unread[sk - 1].post_id
                    }`}
                  >
                    <span>{this.state.unread[sk - 1].post_title}</span>
                  </a>
                  <small className="notification-time">
                    {" "}
                    <TimeDiff
                      date={this.state.unread[sk - 1].created_at}
                    />{" "}
                  </small>
                </li>
              );
            }
            break;
          }
        }
      }
    }
    return rows;
  }

  render() {
    return (
      <React.Fragment>
        <ColumnOne />
        <div className="columnTwo">
          <div className="notification-title">Notifications</div>
          <ul className="notification-list">{this.unReadNotifications()}</ul>
          <ul className="notification-list-u">{this.notifications()}</ul>
        </div>
        <ColumnThree apiROOT={this.props.apiROOT} />
      </React.Fragment>
    );
  }
}

export default Notification;
