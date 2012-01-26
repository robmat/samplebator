#import "ErrorDetailsVC.h"

@implementation ErrorDetailsVC

@synthesize text, errorDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    self.title = @"Error details";
    text.text = errorDetails;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    return YES;
}

@end
