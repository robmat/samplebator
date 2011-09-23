#import "ChooseVideoSourceVC.h"
#import "ifonly_ipAppDelegate.h"
#import "ChooseCategoryVC.h"

@implementation ChooseVideoSourceVC

- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
	self.title = @"Choose video source";
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
- (void)viewDidAppear: (BOOL) animated {
	[super viewDidAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
	NSString* tempFileConf = [ifonly_ipAppDelegate getTempMovieInfoPath];
	NSString* fileUrl = [[info objectForKey:UIImagePickerControllerMediaURL] description];
	NSDictionary* dictTemp = [NSDictionary dictionaryWithObject:fileUrl forKey:@"url"];
	[dictTemp writeToFile:tempFileConf atomically:YES];
	[self.navigationController dismissModalViewControllerAnimated:YES];
	ChooseCategoryVC* ccvc = [[ChooseCategoryVC alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:ccvc animated:YES];
	[ccvc release];
}
- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker {
	[self.navigationController dismissModalViewControllerAnimated:YES];
}
- (void)dealloc {
    [super dealloc];
}

@end
