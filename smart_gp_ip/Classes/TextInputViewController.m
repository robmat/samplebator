#import "TextInputViewController.h"

@implementation TextInputViewController

@synthesize textView, targetTextView;

- (void)viewDidLoad {
    [super viewDidLoad];
	[textView becomeFirstResponder];
}
- (IBAction) okAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidDisappear:(BOOL)animated {
	[super viewDidDisappear:animated];
	targetTextView.text = textView.text;
}
- (void)viewDidUnload {
    [super viewDidUnload];
	targetTextView.text = textView.text;
}
- (void)dealloc {
	[targetTextView release];
	[textView release];
    [super dealloc];
}
@end
