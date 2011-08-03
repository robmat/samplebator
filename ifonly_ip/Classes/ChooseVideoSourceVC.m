#import "ChooseVideoSourceVC.h"
#import "ifonly_ipAppDelegate.h"

@implementation ChooseVideoSourceVC

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (IBAction) cameraAction: (id) sender {
	if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
		NSArray* types = [UIImagePickerController availableMediaTypesForSourceType:UIImagePickerControllerSourceTypeCamera];
		for (NSString* type in types) {
			if ([type isEqualToString:@"public.movie"]) {
				UIImagePickerController* uiipc = [[UIImagePickerController alloc] init];
				uiipc.sourceType = UIImagePickerControllerSourceTypeCamera;
				uiipc.mediaTypes = [NSArray arrayWithObjects:@"public.movie", nil];
				uiipc.delegate = self;
				uiipc.allowsEditing = YES;
				uiipc.videoMaximumDuration = 60;
				[self.navigationController presentModalViewController:uiipc animated:YES];
				[uiipc release];
			}
		}
	}
}

- (IBAction) libraryAction: (id) sender {
	if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypePhotoLibrary]) {
		NSArray* types = [UIImagePickerController availableMediaTypesForSourceType:UIImagePickerControllerSourceTypePhotoLibrary];
		for (NSString* type in types) {
			if ([type isEqualToString:@"public.movie"]) {
				UIImagePickerController* uiipc = [[UIImagePickerController alloc] init];
				uiipc.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
				uiipc.mediaTypes = [NSArray arrayWithObjects:@"public.movie", nil];
				uiipc.delegate = self;
				[self.navigationController presentModalViewController:uiipc animated:YES];
				[uiipc release];
			}
		}
	}
}

- (IBAction) cancelAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
	NSString* tempFileConf = [ifonly_ipAppDelegate getTempMovieInfoPath];
	[info writeToFile:tempFileConf atomically:YES];
	[self.navigationController dismissModalViewControllerAnimated:YES];
}
- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker {
	[self.navigationController dismissModalViewControllerAnimated:YES];
}
- (void)dealloc {
    [super dealloc];
}

@end
