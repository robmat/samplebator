#import "TextInputViewController.h"

@implementation TextInputViewController

@synthesize textView, targetTextView, countLbl;

- (void)viewDidLoad {
    [super viewDidLoad];
	[textView becomeFirstResponder];
	textView.delegate = self;
	editCount = 0;
	delTextAtFirstEdit = YES;
	backBtn.hidden = YES;
}
- (void)textViewDidChange:(UITextView *)textView_ {
	editCount++;
	if (editCount == 2 && delTextAtFirstEdit) {
		textView.text = @"";
	}
	countLbl.text = [[NSNumber numberWithInt:(500 - [textView_.text length])] stringValue];
}
- (IBAction) okAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)viewDidDisappear:(BOOL)animated {
	[super viewDidDisappear:animated];
	if (editCount > 1) { //no input from user
		targetTextView.text = textView.text;
	}
}
- (void)viewDidUnload {
    [super viewDidUnload];
	if (editCount > 1) { //no input from user
		targetTextView.text = textView.text;
	}
}
- (void)dealloc {
	[targetTextView release];
	[textView release];
	[countLbl release];
    [super dealloc];
}
@end
