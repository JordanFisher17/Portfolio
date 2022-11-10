import os
import datetime

from cs50 import SQL
from flask import Flask, flash, redirect, render_template, request, session
from flask_session import Session
from tempfile import mkdtemp
from werkzeug.security import check_password_hash, generate_password_hash


from helpers import apology, login_required, lookup, usd

# Configure application
app = Flask(__name__)

# Ensure templates are auto-reloaded
app.config["TEMPLATES_AUTO_RELOAD"] = True

# Custom filter
app.jinja_env.filters["usd"] = usd

# Configure session to use filesystem (instead of signed cookies)
app.config["SESSION_PERMANENT"] = False
app.config["SESSION_TYPE"] = "filesystem"
Session(app)

# Configure CS50 Library to use SQLite database
db = SQL("sqlite:///finance.db")

# Make sure API key is set
if not os.environ.get("API_KEY"):
    raise RuntimeError("API_KEY not set")


@app.after_request
def after_request(response):
    """Ensure responses aren't cached"""
    response.headers["Cache-Control"] = "no-cache, no-store, must-revalidate"
    response.headers["Expires"] = 0
    response.headers["Pragma"] = "no-cache"
    return response


@app.route("/")
@login_required
def index():
    """Show portfolio of stocks"""
    item = db.execute("SELECT * FROM transactions WHERE user_id = ? AND type = 'Purchased'", session["user_id"])
    user = db.execute("SELECT * FROM users WHERE id = ?", session["user_id"])
    cash = usd(user[0]["cash"])

    for items in item:
        price = lookup(items["symbol"])
        # Update prices on get
        db.execute("UPDATE transactions SET price = ? WHERE symbol = ? AND type = 'Purchased'", price["price"], items["symbol"])

    return render_template("index.html", item=item, cash=cash)


@app.route("/buy", methods=["GET", "POST"])
@login_required
def buy():
    """Buy shares of stock"""
    if request.method == "POST":
        symbol = request.form.get("symbol")

        # Check for valid symbol and number of stocks
        if not symbol:
            return apology("Please enter a valid stock", 400)
        else:
            item = lookup(request.form.get("symbol"))
        if not item:
            return apology("Invalid Stock")
        else:
            stock_number = request.form.get("shares")

        if not stock_number.isdigit() or not int(stock_number) or int(stock_number) < 1:
            return apology("Please enter number of stock", 400)
        else:
            item_price = item["price"]
            total_price = float(item_price) * float(stock_number)
            user_balance = db.execute("SELECT * FROM users WHERE id = ?", session["user_id"])

        # Check the user can afford the stocks
        if total_price > user_balance[0]["cash"]:
            return apology("Insufficient Funds")

        # Add stock in a table on SQL
        else:
            username = session["user_id"]
            company = item["name"]
            symbol = item["symbol"]
            tran_type = "Purchased"
            date = datetime.datetime.now()

            # Add new purchase to the transactions
            db.execute("INSERT INTO transactions(user_id, comp_id, shares_number, price, type, date, symbol) VALUES(?, ?, ?, ?, ?, ?, ?)", username, company, stock_number, item_price, tran_type, date, symbol)

            # Ammend Users cash.
            money_remaining = round(user_balance[0]["cash"] - total_price)
            db.execute("UPDATE users SET cash = ? WHERE id = ?", money_remaining, session["user_id"])

        # Update total prices
            for items in db.execute("SELECT * FROM transactions WHERE user_id = ? AND type = 'Purchased'", session["user_id"]):
                total = float(items["price"]) * int(items["shares_number"])
                db.execute("UPDATE transactions SET total_value = ? WHERE trans_id = ?", total, items["trans_id"])

            # Return to home
            return redirect("/")

    # If GET request
    else:
        return render_template("buy.html")


@app.route("/history")
@login_required
def history():
    """Show history of transactions"""

    user = session["user_id"]
    item = db.execute("SELECT * FROM transactions WHERE user_id = ?", user)

    return render_template("history.html", item=item)


@app.route("/login", methods=["GET", "POST"])
def login():
    """Log user in"""

    # Forget any user_id
    session.clear()

    # User reached route via POST (as by submitting a form via POST)
    if request.method == "POST":

        # Ensure username was submitted
        if not request.form.get("username"):
            return apology("must provide username", 403)

        # Ensure password was submitted
        elif not request.form.get("password"):
            return apology("must provide password", 403)

        # Query database for username
        rows = db.execute("SELECT * FROM users WHERE username = ?", request.form.get("username"))

        # Ensure username exists and password is correct
        if len(rows) != 1 or not check_password_hash(rows[0]["hash"], request.form.get("password")):
            return apology("invalid username and/or password", 403)

        # Remember which user has logged in
        session["user_id"] = rows[0]["id"]

        # Redirect user to home page
        return redirect("/")

    # User reached route via GET (as by clicking a link or via redirect)
    else:
        return render_template("login.html")


@app.route("/logout")
def logout():
    """Log user out"""

    # Forget any user_id
    session.clear()

    # Redirect user to login form
    return redirect("/")


@app.route("/quote", methods=["GET", "POST"])
@login_required
def quote():
    """Get stock quote."""
    if request.method == "GET":
        return render_template("quote.html")

    elif request.method == "POST":
        input_symbol = request.form.get("symbol")

        if not input_symbol:
            return apology("Enter a valid Symbol", 400)
        else:
            quote = lookup(input_symbol)
        if not quote:
            return apology("Enter a valid Symbol", 400)
        else:
            return render_template("quoted.html", quote=quote)


@app.route("/register", methods=["GET", "POST"])
def register():
    """Register user"""
    if request.method == "GET":
        return render_template("register.html")
    else:
        # Check for username and password
        if not request.form.get("username"):
            return apology("must provide username", 400)
        elif not request.form.get("password"):
            return apology("must provide password", 400)
        elif not request.form.get("confirmation"):
            return apology("must confirm password", 400)
        elif request.form.get("password") != request.form.get("confirmation"):
            return apology("passwords do not match", 400)
        # Add new user details to SQL table
        else:
            username = request.form.get("username")
            tmp_password = request.form.get("password")
            password = generate_password_hash(tmp_password)

    # Add if statement to check for duplicates

    if db.execute("SELECT * FROM users WHERE username = ?", username):
        return apology("Username is already taken")
    else:
        db.execute("INSERT INTO users(username, hash) VALUES(?, ?)", username, password)

    return redirect("/")


@app.route("/sell", methods=["GET", "POST"])
@login_required
def sell():
    """Sell shares of stock"""

    symbols = db.execute("SELECT * FROM transactions WHERE user_id = ? AND type = 'Purchased'", session["user_id"])
    item = request.form.get("symbol")
    if request.method == "POST":
        if not item:
            return apology("Please select a symbol to sell", 400)
        else:
            shares = request.form.get("shares")

        if not shares:
            return apology("Enter shares number")
        else:

            username = session["user_id"]
            company = lookup(request.form.get("symbol"))
            comp_id = company["name"]
            item_price = company["price"]
            tran_type = "Sold"
            date = datetime.datetime.now()
            symbol = request.form.get("symbol")

        for stocks in symbols:
            sale = db.execute("SELECT * FROM transactions WHERE user_id = ? AND symbol = ? and shares_number >= ?", username, item, shares)
            if not sale:
                return apology("You do not own those stocks", 400)
            else:
                db.execute("INSERT INTO transactions(user_id, comp_id, shares_number, price, type, date, symbol) VALUES(?, ?, ?, ?, ?, ?, ?)", username, comp_id, shares, item_price, tran_type, date, symbol)
                db.execute("UPDATE transactions SET type = 'Historic' WHERE trans_id = ?", stocks["trans_id"])
                return redirect("/")

        # Update users cash
        user_balance = db.execute("SELECT * FROM users WHERE id = ?", session["user_id"])
        money_made = round(float(item_price) * int(shares) + user_balance[0]["cash"])
        db.execute("UPDATE users SET cash = ? WHERE id = ?", money_made, session["user_id"])

        # Update total price
        for items in db.execute("SELECT * FROM transactions WHERE user_id = ? AND type = 'Sold'", session["user_id"]):
            total = float(items["price"]) * int(items["shares_number"])
            db.execute("UPDATE transactions SET total_value = ? WHERE trans_id = ?", total, items["trans_id"])
    else:
        return render_template("sell.html", symbols=symbols)

