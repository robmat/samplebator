
//Copyright Applicable Ltd 2011

#import "SetReminderViewController.h"

@implementation SetReminderViewController

@synthesize textView, submitLabelText, datePicker, submitLabel, locNot, actionStr;

- (IBAction) setReminder {
	UILocalNotification* ln = [[UILocalNotification alloc] init];
	ln.fireDate = datePicker.date;
	ln.alertBody = [[submitLabelText stringByAppendingString:@" "] stringByAppendingString:textView.text];
	ln.alertAction = @"Ok";
	ln.soundName = UILocalNotificationDefaultSoundName;
	[[UIApplication sharedApplication] scheduleLocalNotification:ln];
	[ln release];
	if (locNot != nil) {
		[[UIApplication sharedApplication] cancelLocalNotification:locNot];
	}
	[self.navigationController popViewControllerAnimated:YES];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Reminder" 
													message:@"Your reminder has been saved." 
												   delegate:nil cancelButtonTitle:@"Ok" 
										  otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.submitLabel.text = submitLabelText;
	self.textView.text = actionStr;
	if ([submitLabel.text rangeOfString:@"CHECK"].location != NSNotFound) {
		[textView setKeyboardType:UIKeyboardTypeNumberPad];
		[[NSNotificationCenter defaultCenter] addObserver:self 
												 selector:@selector(keyboardDidShow:) 
													 name:UIKeyboardDidShowNotification 
												   object:nil];
	}
	self.datePicker.date = [NSDate date];
}
- (void)viewDidUnload {
	[[NSNotificationCenter defaultCenter] removeObserver:self];
}
- (void)keyboardDidShow:(NSNotification *)note {  
	[self performSelector:@selector(addHideKeyboardButtonToKeyboard) withObject:nil afterDelay:0]; 
}
- (void)addHideKeyboardButtonToKeyboard {
	UIWindow *keyboardWindow = nil;
	for (UIWindow *testWindow in [[UIApplication sharedApplication] windows]) {
		if (![[testWindow class] isEqual:[UIWindow class]]) {
			keyboardWindow = testWindow;
			break;
		}
	}
	if (!keyboardWindow) return;
	UIView *foundKeyboard = nil;
	for (UIView *possibleKeyboard in [keyboardWindow subviews]) {
		if ([[possibleKeyboard description] hasPrefix:@"<UIPeripheralHostView"]) {
			possibleKeyboard = [[possibleKeyboard subviews] objectAtIndex:0];
		}                                                                                
		if ([[possibleKeyboard description] hasPrefix:@"<UIKeyboard"]) {
			foundKeyboard = possibleKeyboard;
			break;
		}
	}
	if (foundKeyboard) {
		UIButton *doneButton = [UIButton buttonWithType:UIButtonTypeCustom];
		doneButton.frame = CGRectMake(0, 163, 106, 53);
		doneButton.adjustsImageWhenHighlighted = NO;
		[doneButton setImage:[UIImage imageNamed:@"DoneUp.png"] forState:UIControlStateNormal];
		[doneButton setImage:[UIImage imageNamed:@"DoneDown.png"] forState:UIControlStateHighlighted];
		[doneButton addTarget:self action:@selector(doneButton:) forControlEvents:UIControlEventTouchUpInside];
		[foundKeyboard addSubview:doneButton];
	}
}
-(void)doneButton: (id) sender {
	[textView resignFirstResponder];
}
- (BOOL)textView:(UITextView *)textVie shouldChangeTextInRange:(NSRange)range 
 replacementText:(NSString *)text {
	if ([text isEqualToString:@"\n"]) {
        [textVie resignFirstResponder];
        return FALSE;
    }
    return TRUE;
}
- (void)textViewDidBeginEditing:(UITextView *)textField {
    if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: YES];
	}	
}
- (void)textViewDidEndEditing:(UITextView *)textField {
	if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: NO];
	}
}
- (void) animateTextField: (UITextView*) textField up: (BOOL) up {
    const int movementDistance = 185; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
    int movement = (up ? -movementDistance : movementDistance);
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.view.frame = CGRectOffset(self.view.frame, 0, movement);
    [UIView commitAnimations];
}
- (void)dealloc {
	[textView release];
	[submitLabelText release];
	[datePicker release];
	[locNot release];
	[actionStr release];
	[[NSNotificationCenter defaultCenter] removeObserver:self];
    [super dealloc];
}
@end
